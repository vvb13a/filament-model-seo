@props([
    'attr',
    'minLength' => null,
    'maxLength' => null,
    'baseLength' => 0,
])

<span
    x-data="{
        effMinLength: @js($minLength),
        effMaxLength: @js($maxLength),
        baseLength: @js($baseLength),

        entangledValue: $wire.$entangle('{{ $attr }}').live,

        get currentLength() {
            return (this.entangledValue?.length || 0) + this.baseLength
        },

        isTooShort() {
            return (
                this.effMinLength !== null && this.currentLength < this.effMinLength
            )
        },

        isTooLong() {
            return (
                this.effMaxLength !== null && this.currentLength > this.effMaxLength
            )
        },

        get indicatorStyle() {
            let bgColor
            let textColor = '#ffffff'

            if (this.isTooShort() || this.isTooLong()) {
                bgColor = '#dc2626'
            } else if (
                this.effMinLength === null &&
                this.effMaxLength === null &&
                this.currentLength === 0
            ) {
                bgColor = '#9ca3af'
                textColor = '#374151'
            } else {
                bgColor = '#16a34a'
            }
            return { backgroundColor: bgColor, color: textColor }
        },
    }"
    class="inline-block rounded px-2 py-0.5 text-xs font-medium leading-tight"
    :style="indicatorStyle">
    <span x-show="effMinLength !== null">
        <span x-text="effMinLength"></span>
        <span class="opacity-60">/</span>
    </span>

    <span
        x-text="currentLength"
        :class="{ 'font-semibold': effMinLength !== null || effMaxLength !== null }"></span>

    <span x-show="effMaxLength !== null">
        <span class="opacity-60">/</span>
        <span x-text="effMaxLength"></span>
    </span>
</span>
