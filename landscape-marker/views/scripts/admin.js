const tables = document.querySelectorAll('.table');
const toggles = document.querySelectorAll('h1.toggle');


if (!toggles || !tables || !toggles.length || !tables.length) {
    alert('error!')
}


let current = 0;
for (let i = 0; i < toggles.length; i++) {
    toggles[i].addEventListener('click', e => {
        if (i == current) {
            tables[i].classList.toggle('hidden');
            return;
        }
        tables[i].classList.remove('hidden');
        tables[current].classList.add('hidden');
        current = i;
    })
}