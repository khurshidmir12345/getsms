package uz.smsgateway.sms_gateway

import android.content.BroadcastReceiver
import android.content.Context
import android.content.Intent
import android.provider.Telephony
import android.util.Log

class SmsReceiver : BroadcastReceiver() {
    companion object {
        const val TAG = "SmsReceiver"
        var onSmsReceived: ((String, String) -> Unit)? = null
    }

    override fun onReceive(context: Context?, intent: Intent?) {
        if (intent?.action != Telephony.Sms.Intents.SMS_RECEIVED_ACTION) return

        val messages = Telephony.Sms.Intents.getMessagesFromIntent(intent)
        if (messages.isNullOrEmpty()) return

        // Group by sender to combine multipart messages
        val grouped = messages.groupBy { it.originatingAddress ?: "" }

        for ((sender, parts) in grouped) {
            if (sender.isEmpty()) continue
            val fullBody = parts.joinToString("") { it.messageBody ?: "" }
            Log.d(TAG, "SMS received from: $sender")
            onSmsReceived?.invoke(sender, fullBody)
        }
    }
}
