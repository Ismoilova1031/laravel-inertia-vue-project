<template>
    <div ref="outer" class="outer">
        <div
            class="frame"
            data-certificate-frame
            :style="{
                width: `${CERTIFICATE_WIDTH * scale}px`,
                height: `${CERTIFICATE_HEIGHT * scale}px`,
            }"
        >
            <div
                class="scaler"
                data-certificate-scaler
                :style="{ transform: `scale(${scale})` }"
            >
                <div
                    ref="sheet"
                    class="sheet"
                    :style="{ '--accent': style.color }"
                >
                    <div class="border-outer" />
                    <div class="border-inner" />

                    <svg
                        v-for="corner in corners"
                        :key="corner"
                        :class="['corner', corner]"
                        viewBox="0 0 80 80"
                        aria-hidden="true"
                    >
                        <rect
                            x="0"
                            y="0"
                            width="12"
                            height="12"
                            fill="currentColor"
                        />
                        <path
                            d="M4 76 V4 H76"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="3"
                        />
                        <path
                            d="M16 76 V16 H76"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1"
                        />
                    </svg>

                    <div class="content">
                        <div class="brand">
                            <svg
                                viewBox="0 0 24 24"
                                width="30"
                                height="30"
                                aria-hidden="true"
                            >
                                <path
                                    d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5v-15Zm0 15A2.5 2.5 0 0 0 6.5 23H20v-2H6.5a.5.5 0 0 1 0-1H20"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.6"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            <span>STUDY PLANNER</span>
                        </div>

                        <h1 class="title">Certificate</h1>
                        <div class="subtitle">
                            <span class="rule" /> OF COMPLETION
                            <span class="rule" />
                        </div>

                        <p class="lead">
                            This certificate is proudly presented to
                        </p>
                        <div
                            class="name"
                            :style="{ fontSize: `${nameSize}px` }"
                        >
                            {{ certificate.studentName }}
                        </div>
                        <div class="name-line" />

                        <p class="lead">
                            for successfully completing the course
                        </p>
                        <div class="course">{{ certificate.courseTitle }}</div>

                        <div class="footer">
                            <div class="meta left">
                                <div class="label">Date of issue</div>
                                <div class="value">
                                    {{ certificate.issuedAt }}
                                </div>
                            </div>

                            <div class="seal-wrap">
                                <div class="seal">
                                    <span class="ribbon l" />
                                    <span class="ribbon r" />
                                    <div class="disc">
                                        <div class="disc-inner">
                                            <template v-if="certificate.grade">
                                                <span class="grade">{{
                                                    certificate.grade
                                                }}</span>
                                                <span class="pct"
                                                    >{{
                                                        certificate.percent
                                                    }}%</span
                                                >
                                            </template>
                                            <svg
                                                v-else
                                                viewBox="0 0 24 24"
                                                width="46"
                                                height="46"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    d="M5 12.5l4.5 4.5L19 7.5"
                                                    fill="none"
                                                    stroke="#fff"
                                                    stroke-width="2.6"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="seal-caption">
                                    {{ style.title }}
                                </div>
                            </div>

                            <div class="meta right">
                                <div class="label">Certificate No.</div>
                                <div class="value mono">
                                    {{ certificate.number }}
                                </div>
                                <img
                                    v-if="qr"
                                    :src="qr"
                                    alt="Verification QR code"
                                    class="qr"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import QRCode from "qrcode";
import { computed, onBeforeUnmount, onMounted, ref, watch } from "vue";
import {
    CERTIFICATE_HEIGHT,
    CERTIFICATE_WIDTH,
} from "@/composables/useCertificateExport";
import type { CertificateData } from "@/types/certificate";
import { gradeStyle } from "@/utils/grade";

const props = defineProps<{
    certificate: CertificateData;
    verifyUrl: string;
}>();

const corners = ["tl", "tr", "br", "bl"] as const;

const outer = ref<HTMLElement | null>(null);
const sheet = ref<HTMLElement | null>(null);
const scale = ref(1);
const qr = ref("");

const style = computed(() => gradeStyle(props.certificate.grade));

const nameSize = computed(() => {
    const length = props.certificate.studentName.length;

    return length <= 22 ? 58 : length <= 32 ? 46 : 36;
});

let observer: ResizeObserver | null = null;

function fit() {
    const width = outer.value?.clientWidth ?? CERTIFICATE_WIDTH;
    scale.value = Math.min(1, width / CERTIFICATE_WIDTH);
}

onMounted(() => {
    fit();
    observer = new ResizeObserver(fit);
    if (outer.value) observer.observe(outer.value);
});

onBeforeUnmount(() => observer?.disconnect());

watch(
    () => props.verifyUrl,
    async (url) => {
        qr.value = await QRCode.toDataURL(url, {
            margin: 1,
            width: 220,
            color: { dark: "#1f2937", light: "#ffffff" },
        });
    },
    { immediate: true },
);

defineExpose({ getSheet: () => sheet.value });
</script>

<style scoped>
.outer {
    width: 100%;
}

.frame {
    position: relative;
    margin: 0 auto;
    overflow: hidden;
    border-radius: 6px;
    box-shadow: 0 10px 40px rgba(15, 23, 42, 0.18);
}

.scaler {
    position: absolute;
    top: 0;
    left: 0;
    width: 1123px;
    height: 794px;
    transform-origin: top left;
}

.sheet {
    --ink: #1f2937;
    --muted: #6b7280;
    position: relative;
    width: 1123px;
    height: 794px;
    overflow: hidden;
    color: var(--ink);
    font-family: "Helvetica Neue", Arial, sans-serif;
    background: radial-gradient(circle at 50% 0%, #ffffff 0%, #fbf6ea 100%);
}

.border-outer {
    position: absolute;
    inset: 22px;
    border: 4px solid var(--accent);
}

.border-inner {
    position: absolute;
    inset: 36px;
    border: 1px solid var(--accent);
}

.corner {
    position: absolute;
    width: 80px;
    height: 80px;
    color: var(--accent);
}

.corner.tl {
    top: 22px;
    left: 22px;
}
.corner.tr {
    top: 22px;
    right: 22px;
    transform: rotate(90deg);
}
.corner.br {
    bottom: 22px;
    right: 22px;
    transform: rotate(180deg);
}
.corner.bl {
    bottom: 22px;
    left: 22px;
    transform: rotate(270deg);
}

.content {
    position: absolute;
    inset: 0;
    padding: 58px 110px 52px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.brand {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--accent);
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 0.35em;
}

.title {
    margin: 14px 0 0;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 84px;
    font-weight: 400;
    line-height: 1;
    color: var(--ink);
}

.subtitle {
    display: flex;
    align-items: center;
    gap: 18px;
    margin-top: 8px;
    font-size: 17px;
    font-weight: 700;
    letter-spacing: 0.5em;
    color: var(--accent);
}

.rule {
    display: inline-block;
    width: 90px;
    height: 1px;
    background: var(--accent);
}

.lead {
    margin: 26px 0 0;
    font-size: 17px;
    color: var(--muted);
}

.name {
    margin-top: 12px;
    padding: 0 20px;
    max-width: 100%;
    font-family: Georgia, "Times New Roman", serif;
    font-style: italic;
    line-height: 1.15;
    overflow-wrap: anywhere;
}

.name-line {
    width: 540px;
    height: 2px;
    margin-top: 8px;
    background: linear-gradient(90deg, transparent, var(--accent), transparent);
}

.course {
    margin-top: 10px;
    max-width: 800px;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 32px;
    font-weight: 700;
    line-height: 1.25;
    overflow-wrap: anywhere;
}

.footer {
    margin-top: auto;
    width: 100%;
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: end;
}

.meta .label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--muted);
}

.meta .value {
    margin-top: 4px;
    font-size: 18px;
    font-weight: 600;
}

.meta .mono {
    font-family: "SFMono-Regular", Menlo, Consolas, monospace;
    font-size: 16px;
}

.meta.left {
    text-align: left;
}
.meta.right {
    text-align: right;
}

.qr {
    display: block;
    width: 78px;
    height: 78px;
    margin: 10px 0 0 auto;
}

.seal-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.seal {
    position: relative;
    width: 132px;
    height: 150px;
}

.ribbon {
    position: absolute;
    top: 86px;
    width: 34px;
    height: 62px;
    background: var(--accent);
    filter: brightness(0.82);
}

.ribbon.l {
    left: 30px;
    transform: rotate(14deg);
    clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 82%, 0 100%);
}
.ribbon.r {
    right: 30px;
    transform: rotate(-14deg);
    clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 82%, 0 100%);
}

.disc {
    position: absolute;
    top: 0;
    left: 0;
    width: 132px;
    height: 132px;
    border-radius: 50%;
    background: conic-gradient(
        from 0deg,
        var(--accent),
        #ffffff55,
        var(--accent),
        #ffffff55,
        var(--accent)
    );
    padding: 6px;
}

.disc-inner {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px dashed #ffffffcc;
    background:
        radial-gradient(circle at 35% 30%, #ffffff40, transparent 55%),
        var(--accent);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
}

.grade {
    font-family: Georgia, "Times New Roman", serif;
    font-size: 58px;
    font-weight: 700;
    line-height: 1;
}

.pct {
    margin-top: 2px;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.08em;
}

.seal-caption {
    margin-top: 4px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: var(--accent);
}
</style>
