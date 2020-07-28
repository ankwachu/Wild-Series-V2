let list = document.querySelectorAll(".watchlist");

for (let i=0;i<list.length;i++){
    // console.log(list[i].id)
    list[i].addEventListener('click', addToWatchlist)
}

function addToWatchlist(event) {

    let watchlistIcon = event.target;
    let link = watchlistIcon.dataset.href;

    fetch(link)
        .then(function (res) {
                if (watchlistIcon.classList[2] === "fas") {
                    watchlistIcon.classList.remove('fas');
                    watchlistIcon.classList.add('far');
                    console.log(watchlistIcon.classList)
                } else if (watchlistIcon.classList[0] === "fas") {
                    watchlistIcon.classList.remove('fas');
                    watchlistIcon.classList.add('far');
                    console.log(watchlistIcon.classList)
                } else {
                    watchlistIcon.classList.remove('far');
                    watchlistIcon.classList.add('fas');
                    console.log(watchlistIcon.classList)
                }
            }
        )
}