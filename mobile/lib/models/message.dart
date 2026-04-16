class SmsMessage {
  final int id;
  final String phoneTo;
  final String body;
  final String createdAt;

  SmsMessage({
    required this.id,
    required this.phoneTo,
    required this.body,
    required this.createdAt,
  });

  factory SmsMessage.fromJson(Map<String, dynamic> json) {
    return SmsMessage(
      id: json['id'],
      phoneTo: json['phone_to'],
      body: json['body'],
      createdAt: json['created_at'],
    );
  }
}
