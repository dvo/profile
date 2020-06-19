$(document).ready(function () {
    //let laserExtensionId = "cblibajlakbngnlhjikkphndnnibjbgf";
    //let port = chrome.runtime.connect(laserExtensionId);

    $('#send').on('click', function () {
        let from = $('#from').val();
        let subject = $('#subject').val();
        let msg = $('#message').val();
        $.post(`api.php`, {
            from: from,
            subject: subject,
            message: msg
        }).done(function () {
            $('#message').val("Cheers! Unless you're a spammer, or someone I don't like, I'll get back to your ASAP!");
            /*port.postMessage({
                type: 'count-messages'
            })*/
        });
    });

    $.ajax({
        type: "GET",
        url: "data/public/profile.xml",
        dataType: "xml",
        success: async function (xml) {
            let photo = $(xml).find("photo-url").text();
            let name = $(xml).find("name").text();
            let dob = $(xml).find("dob").text();
            const getAge = birthDate => Math.floor((new Date() - new Date(birthDate).getTime()) / 3.15576e+10);
            let age = await getAge(dob);
            let email = $(xml).find("email").text();
            let phone = $(xml).find("phone").text();
            let city = $(xml).find("city").text();
            let region = $(xml).find("region").text();
            let country = $(xml).find("country").text();
            let website = $(xml).find("website").text();
            let role = $(xml).find("role").text();
            let about = $(xml).find("about").html();
            let employment_status = $(xml).find("employment-status").text();
            let address = `${city}, ${region}, ${country}`;
            let gender = $(xml).find("gender").text();

            $('.about').html(about);
            $('.role').text(role);
            document.title = name;
            $('.name').html(name);
            let firstName = name.split(' ');
            $('.first-name').html(firstName[0]);

            var imgPreload = new Image();
            $(imgPreload).attr({
                src: 'data/public/photos/' + photo
            });

            //check if the image is already loaded (cached):
            if (imgPreload.complete || imgPreload.readyState === 4) {
                $('.profile-photo').html(`<img src="data/public/photos/${photo}">`);
            } else {
                $(imgPreload).on('load', function (response, status, xhr) {
                    if (status == 'error') {
                        console.log('image could not be loaded');
                    } else {
                        $('.profile-photo').html(`<img src="data/public/photos/${photo}">`);
                    }
                });
            }

            $('.basic-info').html(`
                        <table>
                        <tr>
                            <td>Name</td><td>${name}</td>
                        </tr>
                        <tr>
                            <td>Age</td><td>${age}</td>
                        </tr>
                        <tr>
                            <td>Gender</td><td>${gender}</td>
                        </tr>
                        <tr>
                            <td>Email</td><td>${email}</td>
                        </tr>
                        <tr>
                            <td>Phone</td><td><a href="${phone}</a></td>
                        </tr>
                        <tr>
                            <td>Address</td><td>${address}</td>
                        </tr>
                        <tr>
                            <td>Website</td><td><a href="${website}">${website}</a></td>
                        </tr>
                        <tr>
                            <td>Employment Status</td><td>${employment_status}</td>
                        </tr>
                        </table>`);

            let photos = $(xml).find("photos");
            photos.children().each(function () {
                $('.photos-container').append(`<img class="fadeIn" src="data/public/photos/${$(this).text()}" />`);
            });
        }
    });

    $.ajax({
        type: "GET",
        url: "data/public/cv.xml",
        dataType: "xml",
        success: async function (xml) {
            let skills = $(xml).find("skills");
            skills.children().each(async function () {
                $('.skills').append(`<li>${$(this).text()}</li>`);
            });

            let work_history = $(xml).find("work-history");
            work_history.children().each(async function () {
                let from = $(this).find("from").text();
                let to = $(this).find("to").text();
                let company_name = $(this).find("company-name").text();
                let company_url = $(this).find("url").text();
                let job_description = $(this).find("job-description").text();
                $('.work-history').append(`
                        <table><tr>
                            <td>Dates</td><td>${from} - ${to}</td>
                        </tr>
                        <tr>
                            <td>Company Name</td><td>${company_name}</td>
                        </tr>
                        <tr>
                            <td>Website</td><td><a href="${company_url}">${company_url}</a></td>
                        </tr>
                        <tr>
                            <td>Job Description</td><td>${job_description}</td>
                        </tr></table>`);
            });

            let education = $(xml).find("education");
            education.children().each(async function () {
                let from = $(this).find("from").text();
                let to = $(this).find("to").text();
                let course_title = $(this).find("course-title").text();
                let course_description = $(this).find("course-description").html();
                let grade = $(this).find("grade").text();

                $('.education').append(`
                        <table><tr>
                            <td>Dates</td><td>${from} - ${to}</td>
                        </tr>
                        <tr>
                            <td>Course Title</td><td>${course_title}</td>
                        </tr>
                        <tr>
                            <td>Course Description</td><td>${course_description}</td>
                        </tr>
                        <tr>
                            <td>Grade</td><td>${grade}</td>
                        </tr></table>`);
            });
        }
    });
});
