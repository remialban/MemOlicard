var toastTrigger = document.getElementById('liveToastBtn')
var toastLiveExample = document.getElementsByClassName('toast')
Array.prototype.forEach.call(toastLiveExample, element => {
    var toast = new bootstrap.Toast(element)
    toast.show()
});
