'use strict';
const selects = document.getElementsByClassName('category__select');
[...selects].forEach((element) => {
    element.addEventListener('change', function (event) {
        const selectedCategory = this.value;
        console.log(selectedCategory);
    });
});
