package com.eurotaxi.app;

import android.content.SharedPreferences;
import android.util.Log;
import com.capacitorjs.plugins.pushnotifications.MessagingService;

public class EuroTaxiMessagingService extends MessagingService {
    public static final String TAG = "EuroTaxiFCM";
    public static final String PREFS_NAME = "EuroTaxiFCMPrefs";
    public static final String TOKEN_KEY = "fcm_token";

    @Override
    public void onNewToken(String token) {
        // CRITICAL: call super first so Capacitor fires the registration event
        super.onNewToken(token);
        Log.d(TAG, "onNewToken fired! Token: " + token);

        // Store in SharedPreferences so MainActivity can read it immediately
        SharedPreferences prefs = getSharedPreferences(PREFS_NAME, MODE_PRIVATE);
        prefs.edit().putString(TOKEN_KEY, token).apply();

        // Push the token directly to MainActivity's static field
        MainActivity.setSharedToken(token);
    }
}
