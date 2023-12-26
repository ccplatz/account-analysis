'use strict';

import Chart from 'chart.js/auto';

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
    });
});

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
const selectedMonth =
    document.getElementById('monthSelect').selectedOptions[0].value;
const selectedYear =
    document.getElementById('yearSelect').selectedOptions[0].value;
const selectedChart =
    document.getElementById('chartSelect').selectedOptions[0].value;
const chartWrapper = document.getElementById('chartWrapper');

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
    new Chart(document.getElementById('chart'), {
        type: 'bar',
        data: {
            labels: chartData.map((row) => row.category),
            datasets: [
                {
                    label: 'Transactions per category',
                    data: chartData.map((row) => row.value),
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

window.onload = function () {
    axios({
        method: 'post',
        url: '/api/chart/category-by-month',
        data: query,
    })
        .then(function (response) {
            chartData = response.data;

            if (chartData.length > 0) {
                addChart(chartData);
            } else {
                addMissingResultAlert();
            }
        })
        .catch(function (error) {
            console.log(error);
        });
};
