// Функция для добавления элемента в историю поиска
function addToSearchHistory(keyword) {
    const searchHistoryList = document.getElementById('search-history-list');
    const listItem = document.createElement('a');
    listItem.classList.add('catalog_inner__cat_btn');
    listItem.textContent = keyword;
    listItem.href = '/search/?search=' + keyword;
    searchHistoryList.prepend(listItem);
}

// Функция для сохранения истории поиска в Local Storage
function saveSearchHistory(keyword) {
    const searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];
    if (!searchHistory.includes(keyword)) {
        searchHistory.unshift(keyword);
        localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
    }
}

// Функция для загрузки истории поиска из Local Storage
function loadSearchHistory() {
    const searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];
    const searchHistoryList = document.getElementById('search-history-list');
    searchHistoryList.innerHTML = '';
    searchHistory.forEach(keyword => {
        const listItem = document.createElement('a');
        listItem.classList.add('catalog_inner__cat_btn');
        listItem.textContent = keyword;
        listItem.href = '/search/?search=' + keyword;
        searchHistoryList.appendChild(listItem);
    });
}

// Обработчик события для поисковой формы
document.getElementById('search-form').addEventListener('submit', function (event) {
    //event.preventDefault();
    const searchInput = document.getElementById('search-input');
    const keyword = searchInput.value.trim();
    if (keyword !== '') {
        addToSearchHistory(keyword);
        saveSearchHistory(keyword);
        // Здесь можно добавить код для выполнения поиска
        //searchInput.value = ''; // Очищаем поле поиска после добавления
    }
});

// Загрузка истории поиска при загрузке страницы
loadSearchHistory();