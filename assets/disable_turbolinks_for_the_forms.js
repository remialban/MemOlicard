export function run()
{
    var forms = document.getElementsByTagName("form");
    Array.prototype.forEach.call(forms, (form) => {
        form.setAttribute("data-turbo", "false");
    })
}
