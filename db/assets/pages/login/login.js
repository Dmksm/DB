export function login(url) {
    console.log("1");
    console.log(url);
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let obj = JSON.parse(xhr.responseText);
            document.getElementById('result').innerHTML = obj['name'];
        }
        xhr.open('POST', url, true);
        xhr.send();
    }
}