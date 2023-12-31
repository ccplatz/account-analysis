'use strict';

import Chart from 'chart.js/auto';

// Load data and build chart
let chartData = [];
const selectedMonth = +document.getElementById('monthSelect').value;
const selectedYear = +document.getElementById('yearSelect').value;
const chartWrapper = document.getElementById('chartWrapper');
const chartsConfigArr = [];
const chartsConfigSwitchesArr = Array.from(
    document.getElementsByClassName('charts-config-switch')
);
let chart;

let query = {
    month: selectedMonth,
    year: selectedYear,
    chartsConfig: chartsConfigArr,
};

const updateChartsConfig = function () {
    // Empty the array
    chartsConfigArr.splice(0);

    chartsConfigSwitchesArr.forEach((elem) => {
        if (elem.checked) {
            chartsConfigArr.push(elem.value);
        }
    });
};

chartsConfigSwitchesArr.forEach((elem) =>
    elem.addEventListener('change', function (event) {
        // Reload chart if chart options are changed
        updateChartsConfig();
        chart.destroy();
        loadChart();
    })
);

const addMissingResultAlert = function () {
    const para = document.createElement('p');
    para.classList.add('alert', 'alert-warning');
    para.innerText = 'There is no available data.';
    chartWrapper.appendChild(para);
};

const getDatasetsFromChartdata = function (chartData) {
    const labels = Object.values(chartData.categories);
    const getValuesForLabels = function (chartDataPerCategory, labels) {
        return labels.map((label) => {
            const rowWithCategory = chartDataPerCategory.find(
                (row) => row.category === label
            );
            return rowWithCategory ? rowWithCategory.value : 0;
        });
    };
    const chartCatsByPrevMonthIsRequired = function () {
        return chartsConfigArr.includes('categoriesByPrevMonth');
    };
    const chartCatsAvgByLast3MonthIsRequired = function () {
        return chartsConfigArr.includes('categoriesAvgByLast3Month');
    };
    const chartCatsByYearIsRequired = function () {
        return chartsConfigArr.includes('categoriesByYear');
    };
    const chartCatsByTotalTimeIsRequired = function () {
        return chartsConfigArr.includes('categoriesByTotalTime');
    };
    const chartCatsByMonthPrevYearIsReq = function () {
        return chartsConfigArr.includes('categoriesByMonthPrevYear');
    };

    const datasets = [
        {
            label: `Transactions ${
                selectedMonth < 10 ? '0' + selectedMonth : selectedMonth
            }/${selectedYear} per category`,
            data: getValuesForLabels(
                chartData.categoriesByMonthAndYear,
                labels
            ),
        },
    ];

    if (chartCatsByPrevMonthIsRequired()) {
        let prevMonth = +selectedMonth === 1 ? 12 : selectedMonth - 1;
        prevMonth = prevMonth < 10 ? '0' + prevMonth : prevMonth;
        const preMonthYear = prevMonth === 12 ? selectedYear - 1 : selectedYear;
        datasets.push({
            label: `Transactions ${prevMonth}/${preMonthYear} per category`,
            data: getValuesForLabels(chartData.categoriesByPrevMonth, labels),
        });
    }

    if (chartCatsAvgByLast3MonthIsRequired()) {
        datasets.push({
            label: `Average last three month per category`,
            data: getValuesForLabels(
                chartData.categoriesAvgByLast3Month,
                labels
            ),
        });
    }

    if (chartCatsByYearIsRequired()) {
        datasets.push({
            label: `Average ${selectedYear} per category`,
            data: getValuesForLabels(chartData.categoriesByYear, labels),
        });
    }

    if (chartCatsByTotalTimeIsRequired()) {
        datasets.push({
            label: `Average per category`,
            data: getValuesForLabels(chartData.categoriesByTotalTime, labels),
        });
    }

    if (chartCatsByMonthPrevYearIsReq()) {
        datasets.push({
            label: `Transactions ${
                selectedMonth < 10 ? '0' + selectedMonth : selectedMonth
            }/${selectedYear - 1} per category`,
            data: getValuesForLabels(
                chartData.categoriesByMonthPrevYear,
                labels
            ),
        });
    }

    return datasets;
};

const buildChart = function (chartData) {
    const labels = Object.values(chartData.categories);
    const datasets = getDatasetsFromChartdata(chartData);
    Chart.defaults.font.size = 16;
    chart = new Chart(document.getElementById('chart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets,
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const label = context.dataset.label || '';
                            const value = context.parsed.y;
                            const euroValue =
                                value
                                    .toFixed(0)
                                    .replace(/\B(?=(\d{3})+(?!\d))/g, '.') +
                                ' €';
                            return `${label}: ${euroValue}`;
                        },
                    },
                },
            },
            scales: {
                y: {
                    ticks: {
                        // Die Callback-Funktion, um die Werte in Euro zu formatieren
                        callback: function (value, index, values) {
                            return (
                                value
                                    .toFixed(0)
                                    .replace(/\B(?=(\d{3})+(?!\d))/g, '.') +
                                ' €'
                            );
                        },
                    },
                },
            },
        },
    });
};

const addChart = function () {
    const chart = document.createElement('canvas');
    chart.id = 'chart';
    chartWrapper.appendChild(chart);
};

const loadChart = function () {
    axios({
        method: 'post',
        url: '/api/chart/transactions-by-category',
        data: query,
    })
        .then(function (response) {
            chartData = response.data;

            if (chartData.categoriesByMonthAndYear.length < 1) {
                addMissingResultAlert();
                return;
            }

            const chartElem = document.getElementById('chart');
            if (!chartElem) {
                addChart();
            }

            buildChart(chartData);
        })
        .catch(function (error) {
            console.log(error);
        });
};

window.onload = function () {
    updateChartsConfig();
    loadChart();
};

// Update category of transaction
const selects = document.getElementsByClassName('category__select');

[...selects].forEach((element) => {
    element.addEventListener('change', function (event) {
        const selectedCategory = this.value;
        const transaction = this.nextElementSibling.value;
        if (selectedCategory !== '') {
            axios({
                method: 'post',
                url: `/api/transactions/${transaction}/update`,
                data: {
                    category: selectedCategory,
                },
            }).catch(function (error) {
                console.log(error);
            });
        }
        chart.destroy();
        loadChart();
    });
});
