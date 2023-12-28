'use strict';

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
