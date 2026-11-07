import Alpine from 'alpinejs';

const kurangiGerak = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Halaman Tes: satu pertanyaan per layar, perahu berlayar mengikuti progres.
Alpine.data('tes', (qs) => ({
    qs,
    i: 0,
    p: 0,
    jawaban: {},
    memproses: false,
    opsi: ['Tidak pernah', 'Hampir tidak pernah', 'Kadang-kadang', 'Cukup sering', 'Sangat sering'],
    get q() { return this.qs[this.i]; },
    pilih(n) {
        if (!this.q || this.memproses) return;

        this.memproses = true;
        this.jawaban[this.q.id] = n;
        this.p = (this.i + 1) / this.qs.length;

        setTimeout(() => {
            if (this.i + 1 >= this.qs.length) {
                this.$refs.form.submit();
                return;
            }

            this.i++;
            this.memproses = false;
        }, kurangiGerak ? 0 : 320);
    },
    kembali() {
        if (this.memproses) return;

        if (this.i > 0) { this.i--; this.p = this.i / this.qs.length; }
        else window.location = '/';
    },
}));

// Halaman Hasil: skor naik pelan dari 0, penanda gauge meluncur.
Alpine.data('hasil', (skor) => ({
    tampil: kurangiGerak ? skor : 0,
    pos: kurangiGerak ? Math.min(98, Math.max(2, skor / 40 * 100)) : 0,
    init() {
        if (kurangiGerak) return;
        setTimeout(() => { this.pos = Math.min(98, Math.max(2, skor / 40 * 100)); }, 60);
        let t0 = null;
        const f = (ts) => {
            if (!t0) t0 = ts;
            const p = Math.min(1, (ts - t0) / 1300);
            this.tampil = Math.round(skor * (1 - Math.pow(1 - p, 3)));
            if (p < 1) requestAnimationFrame(f);
        };
        requestAnimationFrame(f);
    },
}));

window.Alpine = Alpine;
Alpine.start();
