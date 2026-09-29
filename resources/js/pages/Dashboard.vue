<script setup lang="ts">
import CertificateDashboardController from '@/actions/App/Http/Controllers/CertificateDashboardController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { Form, Head, usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

defineProps<{
    certificates: Array<{
        code: string;
        page_url: string;
        pdf_url: string;
        active: boolean;
    }>;
    certificate: {
        code: string;
        page_url: string;
        pdf_url: string;
        contractor_name: string;
        operation_description: string;
        works_line: string;
        contract_number: string;
        extract_code: string;
        extract_value: string;
        extract_type: string;
        extract_start_date: string;
        extract_end_date: string;
        receipt_no: string;
        amount: string;
        tafqeet: string;
        ministry_code: string;
        clearance_number: string;
        password: string;
        approval_date: string;
    };
}>();

const page = usePage();
const success = computed(() => (page.props.flash as { success?: string } | undefined)?.success);
const error = computed(() => (page.props.flash as { error?: string } | undefined)?.error);

const fieldClass =
    'border-input focus-visible:border-ring focus-visible:ring-ring/50 mt-1 w-full rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:ring-[3px]';

function destroyCertificate(code: string) {
    if (!confirm(`حذف الصفحة /${code} وملف الـ PDF الخاص بيها؟`)) {
        return;
    }

    router.delete(`/dashboard/certificates/${code}`);
}
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-1 flex-col gap-6 p-4">
        <div>
            <h1 class="text-xl font-semibold">صفحات الشهادات</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                كل صفحة لها رابط وملف PDF. الصفحة الجديدة تتنسخ من الصفحة المحددة.
            </p>
        </div>

        <div class="grid gap-4 rounded-xl border p-6 lg:grid-cols-[1.2fr_1fr]">
            <div class="space-y-3">
                <h2 class="text-sm font-semibold">الصفحات الحالية</h2>
                <div
                    v-for="item in certificates"
                    :key="item.code"
                    class="flex flex-wrap items-center justify-between gap-3 rounded-lg border px-3 py-3"
                    :class="item.active ? 'border-primary bg-muted/40' : ''"
                >
                    <div class="space-y-1 text-sm">
                        <a :href="`/dashboard?code=${item.code}`" class="font-medium underline">
                            /{{ item.code }}
                        </a>
                        <div class="flex flex-wrap gap-3 text-xs text-muted-foreground">
                            <a :href="item.page_url" target="_blank" rel="noopener">الصفحة</a>
                            <a :href="item.pdf_url" target="_blank" rel="noopener">ملف PDF</a>
                        </div>
                    </div>
                    <Button
                        v-if="certificates.length > 1"
                        type="button"
                        variant="outline"
                        @click="destroyCertificate(item.code)"
                    >
                        حذف
                    </Button>
                </div>
            </div>

            <Form
                action="/dashboard/certificates"
                method="post"
                class="space-y-3"
                :reset-on-success="['code']"
                v-slot="{ errors: createErrors, processing: creating }"
            >
                <h2 class="text-sm font-semibold">إضافة صفحة جديدة</h2>
                <p class="text-sm text-muted-foreground">
                    نسخة من بيانات
                    <span dir="ltr">/{{ certificate.code }}</span>
                    ومعاها ملف PDF خاص بيها.
                </p>
                <input type="hidden" name="copy_from" :value="certificate.code" />
                <div class="grid gap-2">
                    <Label for="new_code">رقم الرابط الجديد</Label>
                    <Input
                        id="new_code"
                        name="code"
                        class="mt-1"
                        dir="ltr"
                        required
                        placeholder="مثال: 19028002"
                    />
                    <InputError :message="createErrors.code" />
                </div>
                <Button type="submit" :disabled="creating">إضافة صفحة</Button>
            </Form>
        </div>

        <div
            v-if="success"
            class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"
        >
            {{ success }}
        </div>

        <div
            v-if="error"
            class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900"
        >
            {{ error }}
        </div>

        <Form
            :key="certificate.code"
            v-bind="CertificateDashboardController.update.form()"
            class="w-full max-w-5xl space-y-4 rounded-xl border p-6"
            v-slot="{ errors, processing }"
        >
            <div>
                <h2 class="text-lg font-semibold">بيانات /{{ certificate.code }}</h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    الصفحة:
                    <a :href="certificate.page_url" class="underline" target="_blank" rel="noopener">{{ certificate.page_url }}</a>
                    — PDF:
                    <a :href="certificate.pdf_url" class="underline" target="_blank" rel="noopener">{{ certificate.pdf_url }}</a>
                </p>
            </div>
            <input type="hidden" name="code" :value="certificate.code" />
            <div class="grid gap-4 md:grid-cols-2">
                <div class="grid gap-2 md:col-span-2">
                    <Label for="contractor_name">جهة التنفيذ (اسم الشركة)</Label>
                    <Input
                        id="contractor_name"
                        name="contractor_name"
                        class="mt-1"
                        dir="rtl"
                        :default-value="certificate.contractor_name"
                        required
                        placeholder="شركة الحسام للمقاولات العامة والتوريدات"
                    />
                    <InputError :message="errors.contractor_name" />
                </div>

                <div class="grid gap-2 md:col-span-2">
                    <Label for="operation_description">وصف العملية</Label>
                    <textarea
                        id="operation_description"
                        name="operation_description"
                        dir="rtl"
                        required
                        rows="3"
                        :class="fieldClass"
                    >{{ certificate.operation_description }}</textarea>
                    <InputError :message="errors.operation_description" />
                </div>

                <div class="grid gap-2">
                    <Label for="works_line">كود الأعمال</Label>
                    <Input
                        id="works_line"
                        name="works_line"
                        class="mt-1"
                        dir="rtl"
                        :default-value="certificate.works_line"
                        required
                        placeholder="اعمال/ 15740"
                    />
                    <InputError :message="errors.works_line" />
                </div>

                <div class="grid gap-2">
                    <Label for="contract_number">رقم العقد</Label>
                    <Input
                        id="contract_number"
                        name="contract_number"
                        class="mt-1"
                        dir="ltr"
                        :default-value="certificate.contract_number"
                        required
                        placeholder="261008004796"
                    />
                    <InputError :message="errors.contract_number" />
                </div>

                <div class="grid gap-2">
                    <Label for="extract_code">رقم المستخلص</Label>
                    <Input
                        id="extract_code"
                        name="extract_code"
                        class="mt-1"
                        dir="ltr"
                        :default-value="certificate.extract_code"
                        required
                        placeholder="1523469"
                    />
                    <InputError :message="errors.extract_code" />
                </div>

                <div class="grid gap-2">
                    <Label for="extract_value">قيمة المستخلص</Label>
                    <Input
                        id="extract_value"
                        name="extract_value"
                        class="mt-1"
                        dir="ltr"
                        :default-value="certificate.extract_value"
                        required
                        placeholder="8442850"
                    />
                    <InputError :message="errors.extract_value" />
                </div>

                <div class="grid gap-2">
                    <Label for="extract_type">نوع المخالصة</Label>
                    <Input
                        id="extract_type"
                        name="extract_type"
                        class="mt-1"
                        dir="rtl"
                        :default-value="certificate.extract_type"
                        required
                        placeholder="أول وختامي"
                    />
                    <InputError :message="errors.extract_type" />
                </div>

                <div class="grid gap-2">
                    <Label for="receipt_no">رقم الإيصال</Label>
                    <Input
                        id="receipt_no"
                        name="receipt_no"
                        class="mt-1"
                        dir="ltr"
                        :default-value="certificate.receipt_no"
                        required
                        placeholder="20260818436122"
                    />
                    <InputError :message="errors.receipt_no" />
                </div>

                <div class="grid gap-2">
                    <Label for="extract_start_date">تاريخ البداية</Label>
                    <Input
                        id="extract_start_date"
                        name="extract_start_date"
                        class="mt-1"
                        dir="ltr"
                        :default-value="certificate.extract_start_date"
                        required
                        placeholder="24-02-2026"
                    />
                    <InputError :message="errors.extract_start_date" />
                </div>

                <div class="grid gap-2">
                    <Label for="extract_end_date">تاريخ النهاية</Label>
                    <Input
                        id="extract_end_date"
                        name="extract_end_date"
                        class="mt-1"
                        dir="ltr"
                        :default-value="certificate.extract_end_date"
                        required
                        placeholder="24-04-2026"
                    />
                    <InputError :message="errors.extract_end_date" />
                </div>

                <div class="grid gap-2">
                    <Label for="amount">المبلغ</Label>
                    <Input
                        id="amount"
                        name="amount"
                        class="mt-1"
                        dir="ltr"
                        :default-value="certificate.amount"
                        required
                        placeholder="25413"
                    />
                    <InputError :message="errors.amount" />
                </div>

                <div class="grid gap-2">
                    <Label for="ministry_code">كود العملية بالوزارة</Label>
                    <Input
                        id="ministry_code"
                        name="ministry_code"
                        class="mt-1"
                        dir="ltr"
                        :default-value="certificate.ministry_code"
                        required
                        placeholder="421165"
                    />
                    <InputError :message="errors.ministry_code" />
                </div>

                <div class="grid gap-2">
                    <Label for="clearance_number">رقم المخالصة</Label>
                    <Input
                        id="clearance_number"
                        name="clearance_number"
                        class="mt-1"
                        dir="ltr"
                        :default-value="certificate.clearance_number"
                        required
                        placeholder="2253461"
                    />
                    <InputError :message="errors.clearance_number" />
                </div>

                <div class="grid gap-2 md:col-span-2">
                    <Label for="tafqeet">التفقيط</Label>
                    <textarea
                        id="tafqeet"
                        name="tafqeet"
                        dir="rtl"
                        required
                        rows="2"
                        :class="fieldClass"
                    >{{ certificate.tafqeet }}</textarea>
                    <InputError :message="errors.tafqeet" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">كلمة المرور</Label>
                    <Input
                        id="password"
                        name="password"
                        class="mt-1"
                        dir="ltr"
                        :default-value="certificate.password"
                        required
                        placeholder="F22z5s941e"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="approval_date">تاريخ الاعتماد</Label>
                    <Input
                        id="approval_date"
                        name="approval_date"
                        class="mt-1"
                        dir="ltr"
                        :default-value="certificate.approval_date"
                        required
                        placeholder="24-08-2026"
                    />
                    <InputError :message="errors.approval_date" />
                </div>
            </div>

            <div class="pt-2">
                <Button type="submit" :disabled="processing">
                    حفظ وتحديث الشهادة
                </Button>
            </div>
        </Form>
    </div>
</template>
