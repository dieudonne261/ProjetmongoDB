window.onload = function () {
    document.getElementById("download").addEventListener("click", () => {
        const invoice = this.document.getElementById("invoice");
        var opt = {
            margin: 1,
            filename: 'listedeslivres.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().from(invoice).set(opt).save();
    });

    document.getElementById("download2").addEventListener("click", () => {
        const invoice2 = this.document.getElementById("invoice2");
        var opt = {
            margin: 1,
            filename: 'listedesemprunts.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().from(invoice2).set(opt).save(); 
    });

    document.getElementById("download3").addEventListener("click", () => {
        const invoice3 = this.document.getElementById("invoice3");
        var opt = {
            margin: 1,
            filename: 'listedesusers.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().from(invoice3).set(opt).save(); 
    });
}
