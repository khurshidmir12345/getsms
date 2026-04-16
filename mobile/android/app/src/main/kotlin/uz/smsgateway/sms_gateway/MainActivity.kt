package uz.smsgateway.sms_gateway

import android.app.Activity
import android.app.PendingIntent
import android.content.BroadcastReceiver
import android.content.Context
import android.content.Intent
import android.content.IntentFilter
import android.os.Build
import android.telephony.SmsManager
import android.telephony.SubscriptionManager
import android.telephony.TelephonyManager
import io.flutter.embedding.android.FlutterActivity
import io.flutter.embedding.engine.FlutterEngine
import io.flutter.plugin.common.EventChannel
import io.flutter.plugin.common.MethodChannel

class MainActivity : FlutterActivity() {
    private val SMS_CHANNEL = "uz.smsgateway/sms"
    private val SMS_EVENT_CHANNEL = "uz.smsgateway/sms_status"
    private var eventSink: EventChannel.EventSink? = null

    override fun configureFlutterEngine(flutterEngine: FlutterEngine) {
        super.configureFlutterEngine(flutterEngine)

        // Method Channel for sending SMS and getting device info
        MethodChannel(flutterEngine.dartExecutor.binaryMessenger, SMS_CHANNEL).setMethodCallHandler { call, result ->
            when (call.method) {
                "sendSms" -> {
                    val phone = call.argument<String>("phone")
                    val body = call.argument<String>("body")
                    val messageId = call.argument<Int>("messageId")
                    val simSlot = call.argument<Int>("simSlot") ?: 0

                    if (phone != null && body != null && messageId != null) {
                        sendSms(phone, body, messageId, simSlot)
                        result.success(true)
                    } else {
                        result.error("INVALID_ARGS", "Phone, body, and messageId required", null)
                    }
                }
                "getDeviceInfo" -> {
                    result.success(getDeviceInfo())
                }
                "getSimInfo" -> {
                    result.success(getSimInfo())
                }
                "startForegroundService" -> {
                    startForegroundService()
                    result.success(true)
                }
                "stopForegroundService" -> {
                    stopForegroundService()
                    result.success(true)
                }
                else -> result.notImplemented()
            }
        }

        // Event Channel for receiving SMS status updates
        EventChannel(flutterEngine.dartExecutor.binaryMessenger, SMS_EVENT_CHANNEL).setStreamHandler(
            object : EventChannel.StreamHandler {
                override fun onListen(arguments: Any?, events: EventChannel.EventSink?) {
                    eventSink = events
                }
                override fun onCancel(arguments: Any?) {
                    eventSink = null
                }
            }
        )
    }

    private fun sendSms(phone: String, body: String, messageId: Int, simSlot: Int) {
        try {
            val smsManager = if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.S) {
                getSystemService(SmsManager::class.java)
            } else {
                @Suppress("DEPRECATION")
                SmsManager.getDefault()
            }

            val sentAction = "SMS_SENT_$messageId"
            val deliveredAction = "SMS_DELIVERED_$messageId"

            // Register sent receiver
            val sentReceiver = object : BroadcastReceiver() {
                override fun onReceive(context: Context?, intent: Intent?) {
                    val status = when (resultCode) {
                        Activity.RESULT_OK -> "sent"
                        else -> "failed"
                    }
                    val errorMsg = if (resultCode != Activity.RESULT_OK) {
                        "SMS send failed with code: $resultCode"
                    } else null

                    eventSink?.success(mapOf(
                        "type" to "sent",
                        "messageId" to messageId,
                        "status" to status,
                        "errorMessage" to errorMsg
                    ))

                    try { unregisterReceiver(this) } catch (_: Exception) {}
                }
            }

            // Register delivery receiver
            val deliveredReceiver = object : BroadcastReceiver() {
                override fun onReceive(context: Context?, intent: Intent?) {
                    val status = when (resultCode) {
                        Activity.RESULT_OK -> "delivered"
                        else -> "failed"
                    }

                    eventSink?.success(mapOf(
                        "type" to "delivered",
                        "messageId" to messageId,
                        "status" to status
                    ))

                    try { unregisterReceiver(this) } catch (_: Exception) {}
                }
            }

            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
                registerReceiver(sentReceiver, IntentFilter(sentAction), Context.RECEIVER_NOT_EXPORTED)
                registerReceiver(deliveredReceiver, IntentFilter(deliveredAction), Context.RECEIVER_NOT_EXPORTED)
            } else {
                registerReceiver(sentReceiver, IntentFilter(sentAction))
                registerReceiver(deliveredReceiver, IntentFilter(deliveredAction))
            }

            val sentPI = PendingIntent.getBroadcast(this, messageId, Intent(sentAction), PendingIntent.FLAG_IMMUTABLE)
            val deliveredPI = PendingIntent.getBroadcast(this, messageId + 100000, Intent(deliveredAction), PendingIntent.FLAG_IMMUTABLE)

            // Split long messages
            val parts = smsManager.divideMessage(body)
            if (parts.size > 1) {
                val sentIntents = ArrayList<PendingIntent>()
                val deliveredIntents = ArrayList<PendingIntent>()
                for (i in parts.indices) {
                    sentIntents.add(sentPI)
                    deliveredIntents.add(deliveredPI)
                }
                smsManager.sendMultipartTextMessage(phone, null, parts, sentIntents, deliveredIntents)
            } else {
                smsManager.sendTextMessage(phone, null, body, sentPI, deliveredPI)
            }

            // Notify that sending started
            eventSink?.success(mapOf(
                "type" to "sending",
                "messageId" to messageId,
                "status" to "sending"
            ))
        } catch (e: Exception) {
            eventSink?.success(mapOf(
                "type" to "error",
                "messageId" to messageId,
                "status" to "failed",
                "errorMessage" to (e.message ?: "Unknown error")
            ))
        }
    }

    private fun getDeviceInfo(): Map<String, Any?> {
        return mapOf(
            "model" to "${Build.MANUFACTURER} ${Build.MODEL}",
            "androidVersion" to Build.VERSION.RELEASE,
            "sdkVersion" to Build.VERSION.SDK_INT,
            "deviceId" to Build.SERIAL
        )
    }

    private fun getSimInfo(): List<Map<String, Any?>> {
        val result = mutableListOf<Map<String, Any?>>()
        try {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP_MR1) {
                val subscriptionManager = getSystemService(Context.TELEPHONY_SUBSCRIPTION_SERVICE) as SubscriptionManager
                @Suppress("DEPRECATION")
                val subscriptions = subscriptionManager.activeSubscriptionInfoList ?: return result
                for (sub in subscriptions) {
                    result.add(mapOf(
                        "slot" to sub.simSlotIndex,
                        "number" to (sub.number ?: ""),
                        "operator" to (sub.carrierName?.toString() ?: ""),
                        "countryCode" to (sub.countryIso ?: ""),
                    ))
                }
            }
        } catch (_: SecurityException) {
            // Permission not granted
        }
        return result
    }

    private fun startForegroundService() {
        val intent = Intent(this, SmsGatewayService::class.java)
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            startForegroundService(intent)
        } else {
            startService(intent)
        }
    }

    private fun stopForegroundService() {
        val intent = Intent(this, SmsGatewayService::class.java)
        stopService(intent)
    }
}
