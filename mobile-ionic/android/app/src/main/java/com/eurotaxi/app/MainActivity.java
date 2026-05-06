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
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.media.RingtoneManager;
import android.net.Uri;
import android.webkit.ValueCallback;
import androidx.core.app.NotificationCompat;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.HashSet;
import java.util.Set;
import org.json.JSONArray;
import org.json.JSONObject;

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

        // 1.1 Config WebView to allow background autoplay of Web Audio and media without user gestures!
        final Handler autoplayHandler = new Handler(Looper.getMainLooper());
        autoplayHandler.post(new Runnable() {
            @Override
            public void run() {
                try {
                    if (bridge != null && bridge.getWebView() != null) {
                        WebView webView = bridge.getWebView();
                        webView.getSettings().setMediaPlaybackRequiresUserGesture(false);
                        Log.d(TAG, "WebView Media Playback Requires User Gesture set to FALSE!");
                    } else {
                        autoplayHandler.postDelayed(this, 100);
                    }
                } catch (Exception e) {
                    Log.e(TAG, "Failed to configure WebView settings: " + e.getMessage());
                }
            }
        });

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

                        // Capture and save window.LaravelUserId to SharedPreferences!
                        webView.evaluateJavascript("window.LaravelUserId;", new ValueCallback<String>() {
                            @Override
                            public void onReceiveValue(String value) {
                                if (value != null && !value.equals("null") && !value.isEmpty()) {
                                    String userId = value.replace("\"", "");
                                    if (!userId.trim().isEmpty()) {
                                        getSharedPreferences("EuroTaxiPrefs", MODE_PRIVATE)
                                            .edit()
                                            .putString("user_id", userId)
                                            .apply();
                                    }
                                }
                            }
                        });
                    }
                } catch (Exception e) {
                    Log.e(TAG, "Loop error: " + e.getMessage());
                }
                handler.postDelayed(this, 1000);
            }
        });

        // 4. Create native notification channel
        createNotificationChannel();

        // 5. Native Background Polling Thread (100% GMS and FCM Independent!)
        // Runs in the background to ensure alerts appear in the Android Notification Tray outside of the app!
        new Thread(new Runnable() {
            @Override
            public void run() {
                Log.d(TAG, "Native Background Polling Thread Started.");
                
                SharedPreferences prefs = getSharedPreferences("EuroTaxiPrefs", MODE_PRIVATE);
                Set<String> notifiedIds = new HashSet<>();
                String notifiedIdsStr = prefs.getString("notified_alert_ids_csv", "");
                if (!notifiedIdsStr.isEmpty()) {
                    for (String id : notifiedIdsStr.split(",")) {
                        notifiedIds.add(id);
                    }
                }
                boolean isFirstRun = notifiedIds.isEmpty();
                
                while (true) {
                    try {
                        // Wait 8 seconds between polls
                        Thread.sleep(8000);
                        
                        String userId = prefs.getString("user_id", null);
                        if (userId == null || userId.equals("null") || userId.trim().isEmpty()) {
                            continue;
                        }
                        
                        String urlString = "https://eurotaxisystem.site/api/native-poll?user_id=" + userId;
                        URL url = new URL(urlString);
                        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
                        conn.setRequestMethod("GET");
                        conn.setConnectTimeout(5000);
                        conn.setReadTimeout(5000);
                        
                        int responseCode = conn.getResponseCode();
                        if (responseCode == 200) {
                            BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream()));
                            StringBuilder response = new StringBuilder();
                            String line;
                            while ((line = in.readLine()) != null) {
                                response.append(line);
                            }
                            in.close();
                            
                            JSONObject json = new JSONObject(response.toString());
                            if (json.getBoolean("success")) {
                                JSONArray alerts = json.getJSONArray("notifications");
                                
                                notifiedIds = new HashSet<>();
                                String currentCsv = prefs.getString("notified_alert_ids_csv", "");
                                if (!currentCsv.isEmpty()) {
                                    for (String id : currentCsv.split(",")) {
                                        notifiedIds.add(id);
                                    }
                                }
                                boolean needsSave = false;
                                
                                for (int i = 0; i < alerts.length(); i++) {
                                    JSONObject alert = alerts.getJSONObject(i);
                                    String alertId = String.valueOf(alert.getInt("id"));
                                    String title = alert.getString("title");
                                    String message = alert.getString("message");
                                    String type = alert.optString("type", "");
                                    
                                    if (type.equals("test_chime_alert")) {
                                        if (!notifiedIds.contains(alertId)) {
                                            notifiedIds.add(alertId);
                                            needsSave = true;
                                            showNativeSystemNotification(title, message);
                                        }
                                    } else {
                                        if (isFirstRun) {
                                            notifiedIds.add(alertId);
                                            needsSave = true;
                                        } else if (!notifiedIds.contains(alertId)) {
                                            notifiedIds.add(alertId);
                                            needsSave = true;
                                            showNativeSystemNotification(title, message);
                                        }
                                    }
                                }
                                
                                if (isFirstRun && alerts.length() > 0) {
                                    isFirstRun = false;
                                }
                                
                                if (needsSave) {
                                    StringBuilder sb = new StringBuilder();
                                    for (String id : notifiedIds) {
                                        if (sb.length() > 0) sb.append(",");
                                        sb.append(id);
                                    }
                                    prefs.edit().putString("notified_alert_ids_csv", sb.toString()).apply();
                                }
                            }
                        }
                        conn.disconnect();
                    } catch (InterruptedException e) {
                        Log.d(TAG, "Native Poll Thread interrupted.");
                        break;
                    } catch (Exception e) {
                        Log.e(TAG, "Error in Native Background Polling: " + e.getMessage());
                    }
                }
            }
        }).start();
    }

    private void createNotificationChannel() {
        if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.O) {
            CharSequence name = "EuroTaxi Alerts";
            String description = "Real-time system alerts from EuroTaxi System";
            int importance = NotificationManager.IMPORTANCE_HIGH;
            NotificationChannel channel = new NotificationChannel("eurotaxi_bypass_channel", name, importance);
            channel.setDescription(description);
            channel.enableVibration(true);
            channel.enableLights(true);
            
            NotificationManager notificationManager = getSystemService(NotificationManager.class);
            if (notificationManager != null) {
                notificationManager.createNotificationChannel(channel);
            }
        }
    }

    private void showNativeSystemNotification(String title, String message) {
        Log.d(TAG, "Posting Native Tray Notification: " + title);
        
        Intent intent = new Intent(this, MainActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        PendingIntent pendingIntent = PendingIntent.getActivity(
            this, 0, intent, 
            PendingIntent.FLAG_ONE_SHOT | PendingIntent.FLAG_IMMUTABLE
        );
        
        // Dynamically fetch the local ic_launcher mipmap resource which is required by Android to render the banner visibly!
        int iconResourceId = getResources().getIdentifier("ic_launcher", "mipmap", getPackageName());
        int smallIcon = iconResourceId > 0 ? iconResourceId : android.R.drawable.ic_dialog_info;
        
        Uri defaultSoundUri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
        NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(this, "eurotaxi_bypass_channel")
            .setSmallIcon(smallIcon)
            .setContentTitle(title)
            .setContentText(message)
            .setAutoCancel(true)
            .setSound(defaultSoundUri)
            .setPriority(NotificationCompat.PRIORITY_MAX)
            .setDefaults(NotificationCompat.DEFAULT_ALL) // Force Sound, Vibration, and Lights!
            .setContentIntent(pendingIntent);
            
        NotificationManager notificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
        if (notificationManager != null) {
            notificationManager.notify((int) System.currentTimeMillis(), notificationBuilder.build());
        }

        // Trigger a native overlay Toast to guarantee visual text delivery outside the app on ColorOS!
        final String toastText = "🚨 " + title + "\n" + message;
        new Handler(Looper.getMainLooper()).post(new Runnable() {
            @Override
            public void run() {
                try {
                    android.widget.Toast.makeText(MainActivity.this, toastText, android.widget.Toast.LENGTH_LONG).show();
                } catch (Exception e) {
                    Log.e(TAG, "Toast failed: " + e.getMessage());
                }
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
