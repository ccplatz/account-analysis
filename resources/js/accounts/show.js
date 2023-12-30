'use strict';

import Chart from 'chart.js/auto';

// Deactivate month select
const monthSelect = document.getElementById('monthSelect');
const filterSelect = document.getElementById('filterSelect');

if (filterSelect.value === 'year') {
    monthSelect.disabled = true;
}

filterSelect.addEventListener('change', function (event) {
    monthSelect.disabled = !monthSelect.disabled;
});

// Load data and build chart
let chartData = [];
const selectedMonth = document.getElementById('monthSelect').value;
const selectedYear = document.getElementById('yearSelect').value;
const chartWrapper = document.getElementById('chartWrapper');
let chart;

let query = {
    month: selectedMonth,
    year: selectedYear,
};

const addMissingResultAlert = function () {
    const para = document.createElement('p');
    para.classList.add('alert', 'alert-warning');
    para.innerText = 'There is no available data.';
    chartWrapper.appendChild(para);
};

const buildChart = function (chartData) {
    chart = new Chart(document.getElementById('chart'), {
        type: 'bar',
        data: {
            labels: Object.values(chartData[0]),
            datasets: [
                {
                    label: `Transactions ${selectedMonth}/${selectedYear} per category`,
                    data: chartData[1].map((row) => row.value),
                },
                {
                    label: `Average ${selectedYear} per category`,
                    data: chartData[2].map((row) => row.value),
                },
            ],
        },
    });
};

const addChart = function (chartData) {
    const chart = document.createElement('canvas');
    chart.id = 'chart';
    chartWrapper.appendChild(chart);
    buildChart(chartData);
};

const loadChart = function () {
    axios({
        method: 'post',
        url: '/api/chart/category-by-month',
        data: query,
    })
        .then(function (response) {
            chartData = response.data;

            if (chartData[0].length < 1 && chartData[1].length < 1) {
                addMissingResultAlert();
                return;
            }

            addChart(chartData);
        })
        .catch(function (error) {
            console.log(error);
        });
};

window.onload = function () {
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
