# دليل لوحة التحكم وأدوات الإدارة

هذا الملف يشرح لوحة تحكم PG Integrated وطريقة استخدام أقسامها الأساسية.

## 1. الدخول إلى لوحة التحكم

المسار:

`/employee/login`

بعد الدخول ينتقل الموظف إلى:

`/dashboard`

## 2. Dashboard

تحتوي الصفحة الرئيسية على:

- إحصائيات jobs.
- طلبات البريد pending email intake.
- الوظائف العاجلة.
- عدد العملاء.
- عدد المشاريع.
- اختصارات إدارة الموقع.
- Traffic Board مختصر.
- Team Workload.
- Latest Jobs.
- Latest Activities.

## 3. Website

القسم:

`Website → Website Pages`

المسار:

`/admin/cms`

يستخدم لإدارة صفحات الموقع:

- Home.
- About.
- Services.
- Work.
- Team.
- Clients.
- Contact.

كل صفحة لها نموذج منفصل حسب نوعها حتى لا تختلط الحقول.

## 4. Clients

### Clients

المسار:

`/admin/clients`

يستخدم لإضافة العملاء وتفعيل بوابة العميل.

أهم الحقول:

- اسم العميل.
- البريد.
- الشركة.
- Account Manager.
- Enable client portal.
- Portal Password.

### Projects

المسار:

`/admin/projects`

يستخدم لإضافة مشاريع العملاء وربطها بالعميل.

### Client Requests

المسار:

`/admin/client-requests`

يعرض الطلبات التي يرسلها العميل من بوابته:

- Brief.
- Campaign.
- Project.
- Job.
- Attachments.
- External links.

## 5. Traffic

### Email Intake

المسار:

`/email-intakes`

يعرض البريد الوارد من Outlook بعد ربط Microsoft Graph.

يمكن قبول البريد وتحويله إلى Job أو رفضه.

### Jobs

المسار:

`/jobs`

إدارة جميع jobs:

- إنشاء Job.
- متابعة المرحلة.
- رفع المرفقات.
- تعيين الموظفين.
- تحديث موعد التسليم.
- إضافة روابط Dropbox أو Final Delivery.

### Traffic Board

المسار:

`/traffic-board`

يعرض حالة الأعمال حسب مراحل الترافيك.

### Team Workload

المسار:

`/team-workload`

يعرض ضغط العمل على الموظفين.

### Archive

المسار:

`/archive`

يعرض الأعمال المؤرشفة والملفات.

## 6. CRM

المسار:

`/crm`

يستخدم لإدارة:

- الشركات.
- جهات الاتصال.
- الأنشطة.
- المتابعات.
- Lead score.
- LinkedIn URL.
- Website.

## 7. Customer Support

المسار:

`/support`

يستخدم لإدارة التذاكر:

- فتح Ticket.
- الردود.
- التعيين.
- الأولوية.
- الحالة.

## 8. AI Employee

المسار:

`/ai-employee`

مبدأه:

لا يقوم بأي عملية مباشرة بدون موافقة.

يستطيع:

- اقتراح إضافة شركة إلى CRM.
- اقتراح رد على Ticket.
- اقتراح خطوة تالية لطلب عميل.
- مراقبة الأعمال المتأخرة أو المفتوحة.

كل اقتراح يظهر في Pending approvals.

يجب أن يضغط الموظف:

- Approve لتنفيذ الاقتراح.
- Reject لرفضه.

## 9. AI Workspace

المسار:

`/ai-workspace`

يستخدم لتوليد مسودات نصوص من مزود AI مثل:

- OpenAI.
- Gemini.
- Anthropic.

يتطلب إضافة API Key في البيئة.

## 10. Settings

المسار:

`/admin/settings`

يستخدم لإعداد:

- بيانات الشركة.
- Outlook Email Intake.
- Traffic reviewers.
- Operational defaults.
- اختبار اتصال Outlook.
- إنشاء أو تجديد Webhook subscription.

