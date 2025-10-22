document.addEventListener('DOMContentLoaded', function () {
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawCharts);
});

async function drawCharts() {
    try {
        const response = await fetch('../php/get_graficos_data.php');
        const data = await response.json();

        // 1️⃣ Gráfico por Estado
        const dataEstado = new google.visualization.DataTable();
        dataEstado.addColumn('string', 'Estado');
        dataEstado.addColumn('number', 'Total');
        dataEstado.addRows(data.por_estado);

        const chartEstado = new google.visualization.PieChart(document.getElementById('grafico_estado'));

        chartEstado.draw(dataEstado, {
            title: 'Requisiciones por Estado',
            pieHole: 0.4,
            legend: { position: 'right', alignment: 'center' },
            chartArea: { width: '80%', height: '80%' },
            colors: [
                '#FFA500', // Pendiente - Naranja
                '#007BFF', // En proceso - Azul
                '#28A745', // Adquirido - Verde
                '#DC3545'  // Rechazado - Rojo
            ]
        });

        // 2️⃣ Gráfico por Área (colores distintos por barra)
        const dataArea = new google.visualization.DataTable();
        dataArea.addColumn('string', 'Área');
        dataArea.addColumn('number', 'Total');
        dataArea.addColumn({ type: 'string', role: 'style' });

        // 🔹 Paleta de colores personalizados
        const colores = [
            '#3366cc', '#dc3912', '#ff9900', '#109618', '#990099', '#0099c6', '#dd4477',
            '#66aa00', '#b82e2e', '#316395', '#994499', '#22aa99', '#aaaa11', '#6633cc',
            '#e67300', '#8b0707'
        ];

        // 🔹 Agregar filas con color individual
        data.por_area.forEach((fila, i) => {
            dataArea.addRow([fila[0], fila[1], colores[i % colores.length]]);
        });

        const chartArea = new google.visualization.ColumnChart(document.getElementById('grafico_area'));

        const optionsArea = {
            title: 'Requisiciones por Área',
            legend: { position: 'none' },
            bar: { groupWidth: '70%' },
            hAxis: { textStyle: { fontSize: 12 }, slantedText: true },
            vAxis: { minValue: 0 },
            backgroundColor: 'transparent',
            chartArea: { width: '85%', height: '70%' }
        };

        chartArea.draw(dataArea, optionsArea);

        // 3️⃣ Gráfico por Mes
        const dataMes = new google.visualization.DataTable();
        dataMes.addColumn('string', 'Mes');
        dataMes.addColumn('number', 'Requisiciones');
        dataMes.addRows(data.por_mes);
        const chartMes = new google.visualization.LineChart(document.getElementById('grafico_mes'));
        chartMes.draw(dataMes, { title: 'Evolución mensual de Requisiciones', curveType: 'function', legend: { position: 'bottom' } });

        // 4️⃣ Gráfico por Entrega
        /* const dataEntrega = new google.visualization.DataTable();
        dataEntrega.addColumn('string', 'Entrega');
        dataEntrega.addColumn('number', 'Total');
        dataEntrega.addRows(data.por_entrega);
        const chartEntrega = new google.visualization.PieChart(document.getElementById('grafico_entrega'));
        chartEntrega.draw(dataEntrega, { title: 'Estado de Entrega', pieHole: 0.3 }); */
    } catch (error) {
        console.error("Error cargando datos para gráficos:", error);
    }
}
