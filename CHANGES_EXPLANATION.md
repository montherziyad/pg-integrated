# 📝 شرح التعديلات التي تم إضافتها

## 🎯 الهدف الأساسي
تطوير نظام متكامل لإدارة دورة حياة المشاريع الإعلانية من البداية إلى النهاية مع نظام موافقات شامل وتتبع دقيق.

---

## 1️⃣ ماذا تم إضافته؟

### A. جداول قاعدة البيانات الجديدة

#### `job_approvals` table
**الغرض:** تسجيل كل طلب موافقة على مشروع

**الأعمدة:**
- `id` - معرّف فريد
- `creative_job_id` - ربط مع المهمة الإعلانية
- `workflow_stage_id` - أي مرحلة يتطلب موافقة فيها
- `assigned_to_user_id` - الموظف المكلف بالموافقة
- `role` - الدور (مثل: traffic_manager, client_service)
- `status` - الحالة الحالية (pending, approved, rejected, commented)
- `comments` - ملاحظات الموافق
- `approved_at` - تاريخ الموافقة
- `rejected_at` - تاريخ الرفض
- `approval_order` - ترتيب الموافقة (1, 2, 3...)
- `is_required` - هل موافقة إجبارية أم اختيارية
- `approved_by_user_id` - من وافق بالفعل

---

### B. النماذج (Models)

#### `JobApproval.php` (جديد)
**الوظيفة:** تمثيل طلب موافقة واحد

**العلاقات:**
```php
- belongsTo(CreativeJob)     // المهمة المتعلقة بها
- belongsTo(WorkflowStage)   // المرحلة المتطلبة
- belongsTo(User)            // الموظف المكلف بالموافقة
- belongsTo(User)            // الموظف الذي وافق
```

**الدوال:**
- `isPending()` - هل ما تزال قيد الانتظار؟
- `isApproved()` - هل تمت الموافقة؟
- `isRejected()` - هل تم الرفض؟

#### `CreativeJob.php` (محسّن)
**ما تم إضافته:**

1. **العلاقات الجديدة:**
   ```php
   - approvals()           // جميع الموافقات
   - pendingApprovals()    // الموافقات المعلقة فقط
   - revisions()           // التعديلات المطلوبة
   ```

2. **الدوال الجديدة:**
   - `canTransitionTo(stage)` - هل يمكن الانتقال لمرحلة معينة؟
   - `hasRequiredApprovalsForCurrentStage()` - هل تم الحصول على جميع الموافقات المطلوبة؟
   - `moveToStage(stage)` - نقل المهمة لمرحلة جديدة
   - `getCompletionPercentage()` - نسبة إنجاز المهمة
   - `getPendingApprovalsForRole(role)` - الموافقات المعلقة لدور معين
   - `hasApprovalFromRole(role)` - هل توجد موافقة من دور معين؟
   - `requiresApprovalFrom(role)` - هل تحتاج موافقة من دور معين؟
   - `getAllAssignedUsers()` - جميع الموظفين المعينين

---

### C. الخدمات (Services)

#### `JobWorkflowService.php` (جديد)
**الوظيفة:** إدارة منطق الـ workflow

**الدوال الرئيسية:**

1. **إدارة المراحل:**
   ```php
   moveJobToNextStage($job)        // نقل للمرحلة التالية
   moveJobToStage($job, $stage)    // نقل لمرحلة محددة
   canMoveToStage($job, $stage)    // التحقق من الإمكانية
   ```

2. **إدارة الموافقات:**
   ```php
   createApprovalRequirement()      // إنشاء موافقة جديدة
   approveJob($approval, $user)    // الموافقة
   rejectJob($approval, $user)     // الرفض
   areAllApprovalsCompleted()      // هل انتهت الموافقات؟
   ```

3. **الاستعلامات:**
   ```php
   getPendingApprovalsForUser()     // الموافقات المعلقة
   getWorkflowProgression()        // تطور الـ workflow
   ```

4. **الأتمتة:**
   ```php
   createInitialApprovals()         // إنشاء موافقات افتراضية
   logActivity()                    // تسجيل النشاط
   ```

---

### D. المتحكمات (Controllers)

#### `JobApprovalController.php` (جديد)
**الوظيفة:** التعامل مع طلبات الموافقات

**الوظائف:**
- `index()` - عرض الموافقات المعلقة للمستخدم الحالي
- `show()` - عرض تفاصيل موافقة محددة
- `approve()` - الموافقة على طلب
- `reject()` - رفض طلب
- `bulkApprove()` - موافقة جماعية على عدة طلبات
- `jobApprovals()` - عرض موافقات مهمة محددة

---

### E. السياسات (Policies)

#### `JobApprovalPolicy.php` (جديد)
**الوظيفة:** التحكم بمن يمكنه الوصول للموافقات

**الدوال:**
- `view()` - من يمكنه عرض الموافقة؟
- `approve()` - من يمكنه الموافقة؟
- `reject()` - من يمكنه الرفض؟

---

### F. البذور (Seeders)

#### `WorkflowSeeder.php` (جديد)
**الوظيفة:** إدراج مراحل الـ workflow الـ 13 المحددة مسبقًا

**المراحل:**
1. البريد الواصل
2. تحديد الاجتماع
3. البريف المعتمد
4. انتظار موافقة الترافيك ⭐
5. موافقة الترافيك
6. التنفيذ جاري
7. انتظار المراجعة ⭐
8. موافقة المدير ⭐
9. ملاحظات خدمات العملاء
10. تعديلات مطلوبة
11. التسليم النهائي
12. مُسَلَّم للعميل ✓
13. مُلغى ✓

---

### G. الـ Routes

#### المسارات الجديدة في `web.php`:
```php
GET    /approvals                           // عرض الموافقات المعلقة
GET    /approvals/{approval}                // تفاصيل الموافقة
POST   /approvals/{approval}/approve        // الموافقة
POST   /approvals/{approval}/reject         // الرفض
POST   /approvals/bulk-approve              // موافقة جماعية
GET    /jobs/{job}/approvals                // موافقات المهمة
```

---

## 2️⃣ كيف يعمل النظام؟

### السيناريو الواقعي:

```
1. وصول بريد من العميل
   ↓
2. مدير النظام يدخل البريد للمنظومة
   ↓
3. النظام ينشئ JobApproval لمرحلة "brief_approved"
   يحتاج موافقة من: Traffic Manager, Client Service
   ↓
4. Traffic Manager يرى الموافقة المعلقة
   → يوافق أو يرفض
   ↓
5. Client Service يرى الموافقة المعلقة
   → يوافق أو يرفض
   ↓
6. عندما توافق الاثنان معاً:
   → النظام ينقل المهمة للمرحلة التالية
   ↓
7. تكرار العملية لكل مرحلة
   ↓
8. وصول للتسليم النهائي
```

---

## 3️⃣ التدفق البرمجي

### مثال: إنشاء موافقة جديدة

```php
// 1. إنشاء مهمة جديدة
$job = CreativeJob::create([...]);

// 2. الحصول على الخدمة
$service = app(JobWorkflowService::class);

// 3. الحصول على المرحلة
$stage = WorkflowStage::where('code', 'brief_approved')->first();

// 4. إنشاء موافقات افتراضية
$service->createInitialApprovals($job, $stage);

// ✓ النظام يقوم بـ:
// - إنشاء JobApproval لـ Traffic Manager (required)
// - إنشاء JobApproval لـ Client Service (required)
// - إنشاء JobApproval لـ Project Manager (optional)
```

### مثال: الموافقة على طلب

```php
// 1. الحصول على الموافقة
$approval = JobApproval::find(1);

// 2. التحقق من الصلاحيات
if (auth()->user()->can('approve', $approval)) {
    
    // 3. الموافقة
    $service->approveJob($approval, auth()->user(), 'موافق');
    
    // ✓ النظام يقوم بـ:
    // - تحديث حالة الموافقة إلى "approved"
    // - تسجيل معلومات الموافق
    // - تسجيل النشاط
    // - التحقق من الموافقات الأخرى
}
```

### مثال: نقل المرحلة

```php
// 1. التحقق من الإمكانية
if ($service->canMoveToStage($job, $nextStage)) {
    
    // 2. نقل المهمة
    $service->moveJobToStage($job, $nextStage, 'ملاحظات');
    
    // ✓ النظام يقوم بـ:
    // - تحديث current_workflow_stage_id
    // - حفظ السجل في job_stage_histories
    // - تسجيل النشاط
    // - إنشاء موافقات للمرحلة الجديدة
}
```

---

## 4️⃣ الفوائد الرئيسية

### 📊 تتبع شامل
- كل موافقة مسجلة مع من، متى، وماذا
- سجل كامل للتدقيق (Audit Trail)

### 🔐 أمان عالي
- سياسات واضحة للوصول
- التحقق من الأدوار والصلاحيات

### 🎯 مرونة عالية
- يمكن تخصيص الموافقات لكل مهمة
- دعم الموافقات الإجبارية والاختيارية

### ⚡ أتمتة
- تسجيل أنشطة تلقائي
- إنشاء موافقات افتراضية
- نقل تلقائي عند اكتمال الموافقات

### 📱 سهولة الاستخدام
- واجهة واضحة للموافقات
- نموذج بيانات منطقي

---

## 5️⃣ العلاقات بين الجداول

```
CreativeJob
    ↓
    ├── has many JobApproval
    ├── has many JobAssignment
    ├── has many JobActivity
    ├── has many Revision
    ├── has many Asset
    ├── belongs to WorkflowStage (current stage)
    ├── belongs to Client
    ├── belongs to Project
    └── belongs to User (responsible)

JobApproval
    ├── belongs to CreativeJob
    ├── belongs to WorkflowStage
    ├── belongs to User (assigned_to - الموظف المكلف)
    └── belongs to User (approved_by - الموظف الذي وافق)

WorkflowStage
    └── has many JobApproval
    └── has many CreativeJob (current stage)
```

---

## 6️⃣ معدل البيانات المتدفقة

```
1. عند إنشاء مهمة جديدة:
   → إنشاء 3-4 JobApproval
   → تسجيل النشاط

2. عند موافقة:
   → تحديث JobApproval
   → التحقق من الموافقات الأخرى
   → تسجيل النشاط

3. عند نقل مرحلة:
   → تحديث CreativeJob.current_workflow_stage_id
   → حفظ في JobStageHistory
   → إنشاء موافقات جديدة
   → تسجيل النشاط
```

---

## 7️⃣ ملفات التوثيق المضافة

- `WORKFLOW_DOCUMENTATION.md` - توثيق تقني شامل
- `GETTING_STARTED.md` - دليل البدء
- `IMPLEMENTATION_SUMMARY.md` - ملخص الإنجازات
- `CHANGES_EXPLANATION.md` - هذا الملف

