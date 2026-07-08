# توثيق نظام إدارة الـ Workflow

## نظرة عامة

تم تطوير نظام متكامل لإدارة دورة حياة المشاريع الإعلانية من البداية إلى التسليم النهائي، مع نظام موافقات شامل وتتبع دقيق للمراحل.

## المراحل الرئيسية (13 مرحلة)

```
1. البريد الواصل (email_received)
   ↓
2. تحديد الاجتماع (meeting_scheduled)
   ↓
3. البريف المعتمد (brief_approved)
   ↓
4. انتظار موافقة الترافيك (traffic_approval_pending) [مرحلة موافقة]
   ↓
5. موافقة الترافيك (traffic_approved)
   ↓
6. التنفيذ جاري (in_execution)
   ↓
7. انتظار المراجعة (review_pending) [مرحلة موافقة]
   ↓
8. موافقة المدير (leader_approval) [مرحلة موافقة]
   ↓
9. ملاحظات خدمات العملاء (cs_feedback)
   ↓
10. تعديلات مطلوبة (revisions_needed)
    ↓
11. التسليم النهائي (final_delivery)
    ↓
12. مُسَلَّم للعميل (delivered_to_client) [نهائي]
    ↓
13. مُلغى (cancelled) [نهائي]
```

## نظام الموافقات

### الأدوار المطلوبة للموافقة:
- **Traffic Manager** (مدير الترافيك): يوافق على البريف والجدول الزمني
- **Client Service** (خدمات العملاء): يوافق على تفاصيل المشروع
- **Project Leader** (قائد الفريق): يوافق على جودة المخرجات
- **Project Manager** (مدير المشروع): موافقة اختيارية

### حالات الموافقة:
- `pending`: في انتظار الموافقة
- `approved`: تمت الموافقة
- `rejected`: تم الرفض
- `commented`: تم إضافة تعليقات

## الملفات المضافة/المعدلة

### 1. Models (النماذج)

#### `JobApproval.php` (جديد)
- يمثل طلب موافقة واحد
- يحتوي على معلومات الموافق والمرحلة والحالة

#### `CreativeJob.php` (محسّن)
مات الجديدة:
- `approvals()` - الموافقات المتعلقة بالمهمة
- `pendingApprovals()` - الموافقات المعلقة فقط
- `revisions()` - التعديلات المطلوبة
- `canTransitionTo(WorkflowStage)` - التحقق من إمكانية الانتقال
- `hasRequiredApprovalsForCurrentStage()` - التحقق من الموافقات المطلوبة
- `moveToStage(WorkflowStage)` - الانتقال إلى مرحلة جديدة
- `getCompletionPercentage()` - حساب نسبة الإنجاز
- `hasApprovalFromRole(role)` - التحقق من وجود موافقة من دور معين
- `requiresApprovalFrom(role)` - التحقق من الحاجة لموافقة من دور معين

### 2. Services (الخدمات)

#### `JobWorkflowService.php` (جديد)
يوفر:
- `moveJobToNextStage(CreativeJob)` - نقل المهمة للمرحلة التالية
- `moveJobToStage(CreativeJob, WorkflowStage)` - نقل المهمة لمرحلة محددة
- `canMoveToStage(CreativeJob, WorkflowStage)` - التحقق من إمكانية النقل
- `createApprovalRequirement()` - إنشاء متطلب موافقة
- `approveJob(JobApproval, User)` - الموافقة على المهمة
- `rejectJob(JobApproval, User)` - رفض المهمة
- `getPendingApprovalsForUser(User)` - الحصول على الموافقات المعلقة
- `getWorkflowProgression(CreativeJob)` - الحصول على تطور الـ workflow
- `createInitialApprovals(CreativeJob, WorkflowStage)` - إنشاء الموافقات الأولية

### 3. Controllers (المتحكمات)

#### `JobApprovalController.php` (جديد)
المسارات:
- `GET /approvals` - عرض الموافقات المعلقة
- `GET /approvals/{approval}` - تفاصيل موافقة
- `POST /approvals/{approval}/approve` - الموافقة
- `POST /approvals/{approval}/reject` - الرفض
- `POST /approvals/bulk-approve` - موافقة جماعية
- `GET /jobs/{job}/approvals` - موافقات مهمة محددة

### 4. Policies (السياسات)

#### `JobApprovalPolicy.php` (جديد)
تتحكم في:
- `view()` - من يمكنه عرض الموافقة
- `approve()` - من يمكنه الموافقة
- `reject()` - من يمكنه الرفض

### 5. Database (قاعدة البيانات)

#### جدول `job_approvals` (جديد)
الأعمدة:
- `id` - معرف فريد
- `creative_job_id` - ربط مع المهمة
- `workflow_stage_id` - ربط مع المرحلة
- `assigned_to_user_id` - الموظف المكلف بالموافقة
- `role` - الدور المطلوب (traffic_manager, etc)
- `status` - الحالة الحالية
- `comments` - التعليقات
- `approved_at` - تاريخ الموافقة
- `rejected_at` - تاريخ الرفض
- `approval_order` - ترتيب الموافقة
- `is_required` - هل موافقة إجبارية
- `approved_by_user_id` - من وافق

#### جدول `workflow_stages` (محسّن)
- 13 مرحلة محددة مسبقًا
- ألوان وأيقونات لكل مرحلة
- معلومات عن متطلبات المرحلة

## كيفية الاستخدام

### مثال 1: إنشاء موافقات أولية لمهمة جديدة

```php
use App\Services\JobWorkflowService;
use App\Models\CreativeJob;
use App\Models\WorkflowStage;

$service = app(JobWorkflowService::class);
$job = CreativeJob::find(1);
$briefStage = WorkflowStage::where('code', 'brief_approved')->first();

// إنشاء الموافقات الافتراضية
$service->createInitialApprovals($job, $briefStage);
```

### مثال 2: الموافقة على مهمة

```php
use App\Services\JobWorkflowService;
use App\Models\JobApproval;

$service = app(JobWorkflowService::class);
$approval = JobApproval::find(1);
$user = auth()->user();

$service->approveJob($approval, $user, 'التعليقات الاختيارية');
```

### مثال 3: نقل مهمة للمرحلة التالية

```php
use App\Services\JobWorkflowService;
use App\Models\CreativeJob;

$service = app(JobWorkflowService::class);
$job = CreativeJob::find(1);

// التحقق من إمكانية النقل
if ($service->canMoveToStage($job, $nextStage)) {
    $service->moveJobToNextStage($job, 'ملاحظات اختيارية');
}
```

### مثال 4: الحصول على الموافقات المعلقة

```php
use App\Services\JobWorkflowService;

$service = app(JobWorkflowService::class);
$user = auth()->user();

$pendingApprovals = $service->getPendingApprovalsForUser($user);
```

## العلاقات في قاعدة البيانات

```
CreativeJob
├── JobApproval (hasMany) [موافقات المهمة]
├── JobAssignment (hasMany) [تعيينات الموظفين]
├── JobActivity (hasMany) [نشاطات المهمة]
├── Revision (hasMany) [التعديلات]
├── Asset (hasMany) [الملفات]
├── WorkflowStage (belongsTo) [المرحلة الحالية]
├── Client (belongsTo) [العميل]
├── Project (belongsTo) [المشروع]
└── User (belongsTo) [الموظف المسؤول]

JobApproval
├── CreativeJob (belongsTo)
├── WorkflowStage (belongsTo)
├── User (belongsTo) [الموظف المكلف بالموافقة]
└── User (belongsTo) [الموظف الذي وافق]
```

## الـ Routes المضافة

```
GET    /approvals                          - عرض الموافقات المعلقة
GET    /approvals/{approval}               - تفاصيل موافقة
POST   /approvals/{approval}/approve       - الموافقة
POST   /approvals/{approval}/reject        - الرفض
POST   /approvals/bulk-approve             - موافقة جماعية
GET    /jobs/{job}/approvals               - موافقات المهمة
```

## الـ Seeders

### `WorkflowSeeder.php` (جديد)
يعيد إنشاء الـ workflow stages الـ 13

تشغيل:
```bash
php artisan db:seed --class=WorkflowSeeder
```

## الميزات الرئيسية

✅ **نظام موافقات متقدم**: موافقات متسلسلة مع تتبع دقيق
✅ **تحكم بالأدوار**: كل دور له صلاحيات محددة
✅ **تتبع الأنشطة**: تسجيل كامل للتغييرات والموافقات
✅ **مرونة عالية**: يمكن تخصيص الموافقات لكل مهمة
✅ **تقارير شاملة**: معلومات مفصلة عن حالة كل مهمة

## الخطوات التالية

1. **واجهة المستخدم**: إنشاء views للموافقات والـ workflow
2. **الإشعارات**: تنبيهات تلقائية عند الموافقات المعلقة
3. **التقارير**: تقارير عن الموافقات والتأخيرات
4. **التكاملات**: ربط مع الـ email والـ Slack
5. **الأتمتة**: قوالب موافقات مسبقة حسب نوع المهمة

## الملاحظات المهمة

- تأكد من تعيين الموظفين المسؤولين (traffic_manager, project_manager, etc)
- جميع الموافقات المعلقة يجب أن تُكمل قبل نقل المهمة للمرحلة التالية
- يتم تسجيل جميع الأنشطة تلقائيًا في جدول `job_activities`
- يمكن إضافة تعليقات على كل موافقة

---

تم إنشاء هذا النظام بناءً على متطلبات وكالة دعاية وإعلان PG Integrated
