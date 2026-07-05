# ربط Outlook وخطة تفريغ قاعدة البيانات واستيراد بيانات حقيقية

هذا الملف يشرح إمكانية ربط النظام مع Outlook واستقبال البريد، ثم خطة آمنة لتفريغ قاعدة البيانات وإدخال بيانات حقيقية من ملفات العملاء والمشاريع.

## 1. هل يمكن ربط Outlook الآن؟

نعم، النظام يحتوي حالياً على أساس ربط Outlook عبر Microsoft Graph:

- إعدادات Outlook داخل `/admin/settings`.
- اختبار الاتصال.
- إنشاء Webhook subscription.
- استقبال إشعارات البريد عبر `/api/outlook/webhook`.
- تحويل البريد إلى Email Intake.
- قبول Email Intake وتحويله إلى Job.

## 2. البريد المعتمد للترافيك

يمكن استخدام:

`Mziyad@pgintegrated.com`

كالبريد الذي تتم مراقبته لاستقبال طلبات الترافيك.

يفضل توحيد الكتابة داخل الإعدادات كالتالي:

`mziyad@pgintegrated.com`

لأن البريد الإلكتروني غير حساس لحالة الأحرف عادة، لكن التوحيد يقلل الالتباس.

## 3. متطلبات Microsoft 365 / Azure

تحتاج إلى:

1. Microsoft Tenant ID.
2. Application Client ID.
3. Client Secret.
4. صلاحيات Microsoft Graph للبريد.
5. السماح للتطبيق بقراءة mailbox الخاص بـ `mziyad@pgintegrated.com`.
6. Webhook URL عام يمكن لـ Microsoft الوصول له.

## 4. إعدادات النظام

من لوحة التحكم:

`/admin/settings`

املأ:

- Enable Outlook intake.
- Microsoft Tenant ID.
- Application Client ID.
- Monitored Mailbox: `mziyad@pgintegrated.com`
- Company Email Domain: `pgintegrated.com`
- Job Number Pattern.
- Maximum Attachment Size.
- Allowed Brief Extensions.
- Traffic Members.

وفي ملف `.env`:

```dotenv
OUTLOOK_TENANT_ID=
OUTLOOK_CLIENT_ID=
OUTLOOK_CLIENT_SECRET=
OUTLOOK_MAILBOX=mziyad@pgintegrated.com
OUTLOOK_WEBHOOK_CLIENT_STATE=ضع_قيمة_سرية_طويلة
```

## 5. اختبار Outlook

بعد حفظ الإعدادات:

1. اضغط `Test Connection`.
2. إذا نجح الاتصال، اضغط `Create / Renew Subscription`.
3. أرسل بريد تجربة إلى `mziyad@pgintegrated.com`.
4. راجع `/email-intakes`.

## 6. نقطة مهمة حول Webhook

Microsoft Graph يحتاج رابط عام HTTPS.

إذا كنت تعمل محلياً على:

`http://127.0.0.1:8010`

فلن يستطيع Microsoft الوصول للـ webhook مباشرة.

الحلول:

- رفع النظام على سيرفر staging/production.
- أو استخدام Tunnel مؤقت مثل ngrok/Cloudflare Tunnel أثناء التجربة.

## 7. هل يمكن تفريغ قاعدة البيانات؟

نعم، لكن لا أنصح بتفريغها مباشرة بدون:

1. Backup كامل.
2. Export من الجداول الحالية.
3. حفظ نسخة من `.env`.
4. تحديد الملفات الحقيقية التي سيتم استيرادها.
5. Mapping بين أعمدة الملفات وجداول النظام.

## 8. خطة آمنة لإدخال بيانات حقيقية

الترتيب المقترح:

1. أخذ نسخة احتياطية من قاعدة البيانات الحالية.
2. رفع ملفات العملاء والمشاريع والموظفين.
3. فحص الملفات وتحديد الأعمدة.
4. تجهيز ملف mapping.
5. إنشاء Import Script أو Seeder خاص.
6. تجربة الاستيراد على نسخة staging.
7. مراجعة النتائج.
8. عند الموافقة، تنفيذ الاستيراد على قاعدة الإنتاج.

## 9. الملفات التي يمكن استيرادها

يمكن إدخال بيانات من:

- Excel.
- CSV.
- Google Sheets export.
- ملفات مشاريع.
- ملفات عملاء.
- قوائم موظفين.
- أرشيف jobs.
- ملفات brief.

## 10. ترتيب الاستيراد الصحيح

يجب أن يكون الاستيراد بهذا الترتيب:

1. Branches.
2. Teams.
3. Roles.
4. Users.
5. Clients.
6. Projects.
7. Categories.
8. Jobs.
9. Attachments / Assets.
10. CRM companies and contacts.

## 11. ما أحتاجه منك قبل التفريغ والاستيراد

أرسل الملفات التي تحتوي:

- العملاء.
- المشاريع.
- الموظفين.
- jobs إن وجدت.
- التصنيفات.
- أي ملفات مرفقات أو روابط.

ثم أراجعها وأعطيك:

- الجداول التي ستتأثر.
- الأعمدة المطلوبة.
- أي بيانات ناقصة.
- خطة الاستيراد.

لا يتم حذف أو تفريغ قاعدة البيانات إلا بعد موافقتك الصريحة.

