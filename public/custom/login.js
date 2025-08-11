let baseUrl = "/login";
$(document).on("click", ".login", function (e) {
    e.preventDefault();

    let email = $("#email").val();
    let password = $("#password").val();
    let spinner = $(this).find(".spinner-border");

    spinner.removeClass("visually-hidden");
    $(this).attr("disabled", "disabled");

    $.ajax({
        url: baseUrl,
        method: "POST",
        data: {
            email: email,
            password: password,
            _token: csrfToken,
        },
        dataType: "json",
        success: function (data) {
            if (data.status == "true") {
                Swal.fire({
                    title: data.title,
                    text: data.description,
                    icon: data.icon,
                    allowOutsideClick: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 3000,
                    didOpen: () => {
                        Swal.showLoading();
                        const timerInterval = setInterval(() => {
                            const content = Swal.getContent();
                            if (content) {
                                const progressBar =
                                    content.querySelector("progress");
                                if (progressBar) {
                                    progressBar.value += 20;
                                }
                            }
                        }, 400);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    },
                    onClose: () => {
                        window.location = "/dashboard/overview";
                    },
                });
            } else {
                Swal.fire(data.title, data.description, data.icon).then(
                    function () {
                    }
                );
            }
        },
        complete: function () {
            spinner.addClass("visually-hidden");
            $(".login").removeAttr("disabled");
        },
    });
});
