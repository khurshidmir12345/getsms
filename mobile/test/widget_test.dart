import 'package:flutter_test/flutter_test.dart';
import 'package:sms_gateway/main.dart';

void main() {
  testWidgets('App loads', (WidgetTester tester) async {
    await tester.pumpWidget(const SmsGatewayApp());
    expect(find.text('SMS Gateway'), findsAny);
  });
}
