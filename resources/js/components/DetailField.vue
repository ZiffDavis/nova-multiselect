<template>
    <panel-item :field="field">
        <div slot="value" class="flex flex-wrap">
            <div v-for="(item) in field.value" class="flex-none rounded-lg cursor-default text-center text-white bg-primary px-2 py-1 mr-1 mb-1">
                <span>{{ item[field.optionLabel] }}</span>
            </div>
        </div>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
