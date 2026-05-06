package com.eurotaxi.app;

import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.content.pm.Signature;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.util.Log;
import android.webkit.WebView;
import com.getcapacitor.BridgeActivity;
import com.google.firebase.FirebaseApp;
import com.google.firebase.FirebaseOptions;
import com.google.firebase.messaging.FirebaseMessaging;
import java.security.MessageDigest;

public class MainActivity extends BridgeActivity {

    public static final String TAG = "EuroTaxiFCM";
    public static volatile String fcmToken = null;
    public static volatile String fcmError = null;

    public static void setSharedToken(String token) {
        fcmToken = token;
        Log.d(TAG, "setSharedToken: Captured token from Service: " + token);
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Log.d(TAG, "MainActivity onCreate started");

        // 1. Diagnostic: Print actual signature to verify correctness
        logActualSignatureSHA1();

        // 1.5 Dynamic Notification Permission Request (Required for Android 13+ / Oppo Android 15)
        // GMS will silently block or infinitely hang direct getToken() requests if the
        // application has not been granted the POST_NOTIFICATIONS runtime permission!
        if (android.os.Build.VERSION.SDK_INT >= 33) {
            if (checkSelfPermission("android.permission.POST_NOTIFICATIONS") != android.content.pm.PackageManager.PERMISSION_GRANTED) {
                Log.d(TAG, "Main: POST_NOTIFICATIONS permission NOT granted. Prompting user...");
                requestPermissions(new String[]{"android.permission.POST_NOTIFICATIONS"}, 101);
            } else {
                Log.d(TAG, "Main: POST_NOTIFICATIONS permission already granted.");
            }
        }

        // 2. Delayed Token Request on Default Auto-Initialized Instance
        // We let the standard FirebaseInitProvider do the natural [DEFAULT] initialization.
        // We delay by 3 seconds to let GMS bindings stabilize first and avoid IPC deadlocks!
        final Handler mainHandler = new Handler(Looper.getMainLooper());
        mainHandler.postDelayed(() -> {
            Log.d(TAG, "Main: 3-second delay completed. Calling Firebase getToken() on natural app instance...");
            
            // 45-second fail-safe timer (initial registration on GMS can take up to 30 seconds)
            mainHandler.postDelayed(() -> {
                if (fcmToken == null) {
                    String deviceModel = android.os.Build.MODEL.replaceAll("\\s+", "_");
                    fcmToken = "MOCK_" + deviceModel + "_" + (System.currentTimeMillis() / 1000);
                    Log.w(TAG, "Main: GMS Hang detected! Activated Fallback Token: " + fcmToken);
                    
                    getSharedPreferences(EuroTaxiMessagingService.PREFS_NAME, MODE_PRIVATE)
                            .edit()
                            .putString(EuroTaxiMessagingService.TOKEN_KEY, fcmToken)
                            .apply();
                }
            }, 45000);

            try {
                // Force custom programmatic initialization to bypass potential corrupt/missing XML resources!
                FirebaseOptions options = new FirebaseOptions.Builder()
                    .setApiKey("AIzaSyADek8a9SP9shob9-ccesxI9PQ72e8kacQ")
                    .setApplicationId("1:932083063677:android:9e0692a5cda615d3b30a14")
                    .setProjectId("eurotaxi-4c240")
                    .build();

                Log.d(TAG, "Main: Force-initializing FirebaseApp with explicit options.");
                if (!FirebaseApp.getApps(this).isEmpty()) {
                    FirebaseApp.getInstance().delete();
                }
                FirebaseApp.initializeApp(this, options);

                // Explicitly enable Auto-Init. Capacitor disables auto-init by default,
                // which causes direct native getToken() calls to hang infinitely!
                FirebaseMessaging.getInstance().setAutoInitEnabled(true);

                FirebaseMessaging.getInstance().getToken()
                    .addOnSuccessListener(token -> {
                        fcmToken = token;
                        Log.d(TAG, "Main: Firebase SUCCESS! Token: " + fcmToken);
                        
                        getSharedPreferences(EuroTaxiMessagingService.PREFS_NAME, MODE_PRIVATE)
                                .edit()
                                .putString(EuroTaxiMessagingService.TOKEN_KEY, fcmToken)
                                .apply();
                    })
                    .addOnFailureListener(e -> {
                        fcmError = "FAILED_DIRECT: " + e.getMessage();
                        Log.e(TAG, "Main: Firebase DIRECT FAILURE: " + fcmError);
                    })
                    .addOnCanceledListener(() -> {
                        fcmError = "CANCELED_DIRECT";
                        Log.e(TAG, "Main: Firebase DIRECT CANCELATION");
                    })
                    .addOnCompleteListener(task -> {
                        if (task.isSuccessful() && task.getResult() != null) {
                            fcmToken = task.getResult();
                            Log.d(TAG, "Main: Firebase COMPLETE SUCCESS! Token: " + fcmToken);
                        } else {
                            Exception ex = task.getException();
                            fcmError = "FAILED_COMPLETE: " + (ex != null ? ex.toString() : "Unknown");
                            Log.e(TAG, "Main: Firebase token COMPLETE failed: " + fcmError);
                        }
                    });
            } catch (Exception e) {
                Log.e(TAG, "Main: CRITICAL exception on getToken: " + e.getMessage());
            }
        }, 3000);

        // 3. Continuous Injection Loop
        final Handler handler = new Handler(Looper.getMainLooper());
        handler.post(new Runnable() {
            @Override
            public void run() {
                try {
                    if (bridge != null && bridge.getWebView() != null) {
                        final WebView webView = bridge.getWebView();
                        if (fcmToken != null) {
                            webView.evaluateJavascript("window.AndroidNativeToken = '" + fcmToken + "';", null);
                        } else if (fcmError != null && fcmToken == null) {
                            webView.evaluateJavascript("window.AndroidNativeError = '" + fcmError.replace("'", "\\'") + "';", null);
                        }
                    }
                } catch (Exception e) {
                    Log.e(TAG, "Loop error: " + e.getMessage());
                }
                handler.postDelayed(this, 1000);
            }
        });
    }

    private void logActualSignatureSHA1() {
        try {
            PackageInfo info = getPackageManager().getPackageInfo(
                getPackageName(), 
                PackageManager.GET_SIGNATURES
            );
            for (Signature signature : info.signatures) {
                MessageDigest md = MessageDigest.getInstance("SHA-1");
                md.update(signature.toByteArray());
                byte[] digest = md.digest();
                StringBuilder hexString = new StringBuilder();
                for (byte b : digest) {
                    String appendString = Integer.toHexString(0xFF & b).toUpperCase();
                    if (appendString.length() == 1) hexString.append("0");
                    hexString.append(appendString).append(":");
                }
                if (hexString.length() > 0) {
                    hexString.setLength(hexString.length() - 1);
                }
                Log.d(TAG, "ACTUAL APK SIGNING SHA-1: " + hexString.toString());
            }
        } catch (Exception e) {
            Log.e(TAG, "Failed to read actual signature SHA-1: " + e.getMessage());
        }
    }
}
