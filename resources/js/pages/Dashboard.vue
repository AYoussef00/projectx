<script setup lang="ts">
import CertificateDashboardController from '@/actions/App/Http/Controllers/CertificateDashboardController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { Form, Head, usePage } from '@inertiajs/vue3';
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
    certificate: {
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
        password: string;
        approval_date: string;
    };
}>();

const page = usePage();
const success = computed(() => (page.props.flash as { success?: string } | undefined)?.success);

const fieldClass =
    'border-input focus-visible:border-ring focus-visible:ring-ring/50 mt-1 w-full rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:ring-[3px]';
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-1 flex-col gap-6 p-4">
        <div>
            <h1 class="text-xl font-semibold">تعديل بيانات الشهادة</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                القيم هنا بتتحدث في الصفحة الرئيسية وفي ملف
                <code class="text-xs">/sample/certificate.pdf</code>
                من غير تغيير التصميم.
            </p>
        </div>

        <div
            v-if="success"
            class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"
        >
            {{ success }}
        </div>

        <Form
            v-bind="CertificateDashboardController.update.form()"
            class="w-full max-w-5xl space-y-4 rounded-xl border p-6"
            v-slot="{ errors, processing }"
        >
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
