<script setup>
import { computed } from "vue";
import dayjs from "dayjs";
import "dayjs/locale/id";
const props = defineProps({
    siswa: Object,
    rombel: Object,
    kebiasaan: String,
    bulanTahun: String,
});
const datas = computed(() =>
    props.siswa.kaihs.filter((k) => {
        return k.kebiasaan === props.kebiasaan;
    }),
);

const foto = computed(() => {
    return (
        props.siswa.foto ||
        (props.siswa.jk == "Laki-laki"
            ? "/img/siswa.png"
            : props.siswa.agama == "Islam"
              ? "/img/siswi-is.png"
              : "/img/siswi.png")
    );
});

const items = computed(() => {
    const dates = [];
    const startDate = dayjs(props.bulanTahun + "-01");
    const daysInMonth = startDate.daysInMonth();
    for (let i = 0; i < daysInMonth; i++) {
        const currentDate = startDate.add(i, "day").format("YYYY-MM-DD");
        const matched = datas.value.find(
            (item) => dayjs(item.waktu).format("YYYY-MM-DD") === currentDate,
        );
        dates.push({
            tanggal: currentDate,
            kebiasaan: matched ? matched.kebiasaan : null,
            status: matched ? matched.is_done : null,
            waktu: matched ? dayjs(matched.waktu).format("HH:mm") : null,
        });
    }
    return dates;
});
</script>

<template>
    <div class="wrapper h-screen w-full bg-slate-100 p-4">
        <h3
            class="text-center uppercase font-black font-['Arial'] text-xl mb-8"
        >
            DETAIL {{ kebiasaan }} BULAN
            {{ dayjs(bulanTahun).locale("id").format("MMMM YYYY") }}
        </h3>

        <img
            :src="foto"
            alt="Foto Siswa"
            class="rounded-full w-24 h-24 mx-auto"
        />
        <h4 class="text-center mt-2 text-sky-700 font-bold">
            {{ siswa.nama }}
        </h4>
        <div
            class="flex gap-3 flex-wrap my-6 justify-center md:w-[60%] w-full mx-auto"
        >
            <template v-for="(item, i) in items" :key="i">
                <ElTooltip
                    :content="
                        item.kebiasaan
                            ? `Dilakukan: ${item.waktu}`
                            : 'Tidak dilakukan'
                    "
                    :popper-class="!item.kebiasaan ? 'hidden' : 'show-tooltip'"
                >
                    <div
                        class="flex flex-col items-center justify-center h-[100px] w-[100px] p-2 border rounded relative cursor-pointer hover:shadow-lg transition-all duration-300 group"
                        :class="item.kebiasaan ? 'bg-white' : 'bg-gray-300'"
                    >
                        <span
                            class="absolute right-0 top-1 left-0 text-center"
                            >{{
                                dayjs(item.tanggal).locale("id").format("dddd")
                            }}</span
                        >
                        <h3
                            class="black font-['Arial-Black'] text-3xl group-hover:text-4xl transition-all duration-300"
                        >
                            {{ dayjs(item.tanggal).format("DD") }}
                        </h3>
                        <span
                            class="absolute right-0 bottom-0 left-0 bg-teal-300 bg-opacity-60 text-center leading-4 font-bold text-xs px-2"
                            >{{ item.kebiasaan }}</span
                        >
                    </div>
                </ElTooltip>
            </template>
        </div>
    </div>
</template>
