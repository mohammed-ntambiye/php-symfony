var titleElem = document.getElementById("reviewer_reviewbundle_book_title");
var authorElem = document.getElementById("reviewer_reviewbundle_book_author");
var isbnElem = document.getElementById("reviewer_reviewbundle_book_isbn");
var synopsisElem = document.getElementById("reviewer_reviewbundle_book_bookDescription");
var bookImage = document.getElementById("reviewer_reviewbundle_book_imageHolder");
var suggestionsElem = document.getElementById("suggestionsTable");

var lastTitle, lastAuthor;

function valueHasChanged(title, author) {
    if (title !== lastTitle) {
        lastTitle = title;
        return true;
    }
    if (author !== lastAuthor) {
        lastAuthor = author;
        return true;
    }
    return false;
}

function get(url, callback) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            callback(JSON.parse(xmlhttp.responseText));
        }
    }
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function findSuggestedBooks(title, author) {
    if (valueHasChanged(title, author)) {
        var url = "http://" + window.location.host + "/api/v1/books";

        if (title) {
            url += "?title=" + title;
        }
        if (author) {
            title ? url += "&author=" : url += "?author=";
            url += author;
        }

        get(url, function (matches) {
            suggestionsElem.parentElement.classList.add("visible");
            suggestionsElem.innerHTML = '';
            matches.forEach(function (book) {
                var image = '<td><img src="' + book.cover_image + '" alt="Book cover"></td>';
                var details = '<td><h4>' + book.title + '</h4><p>Author: ' + book.author + '</p><p>Publisher: ' + book.publisher + '</p><p>Publication date: ' + book.publish_date + '</p><p>ISBN: ' + book.isbn + '</p></td>';
                suggestionsElem.innerHTML += '<tr data-isbn="' + book.isbn + '" data-title="' + book.title + '" data-author="' + book.author + '" data-image="' + book.cover_image + '" data-synopsis="' + book.synopsis + '">' + image + details + '</tr>';
            });
            document.querySelectorAll("#suggestionsTable tr").forEach(function (row) {
                row.addEventListener("click", function () {
                    titleElem.value = this.dataset.title;
                    authorElem.value = this.dataset.author;
                    isbnElem.value = this.dataset.isbn;
                    var temp = this.dataset.image;
                    $("#reviewer_reviewbundle_book_imageHolder").val(temp)
                    synopsisElem.value = this.dataset.synopsis.substr(0,250);
                });
            });
        });
    }
}