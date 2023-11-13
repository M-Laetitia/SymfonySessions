
    //^ Show/hide past sessions (list)---------------------

    $(document).ready(function () {
        $('#show-past-sessions-btn').click(function () {
            console.log("ok")
                $('#past-sessions-container').toggle();
            },
            
        );

    });

    $(document).ready(function () {
        // On hover, toggle classes for both i and a elements
        $('#team-icon, #team-link').hover(
            function () {
                $('#team-icon').toggleClass('ri-team-line ri-team-fill');
            },
            function () {
                $('#team-icon').toggleClass('ri-team-line ri-team-fill');
            }
        );
    
        $('#repository-icon, #repository-link').hover(
            function () {
                $('#repository-icon').toggleClass('ri-git-repository-line ri-git-repository-fill');
            },
            function () {
                $('#repository-icon').toggleClass('ri-git-repository-line ri-git-repository-fill');
            }
        );
    
        $('#dashboard-icon, #dashboard-link').hover(
            function () {
                $('#dashboard-icon').toggleClass('ri-dashboard-line ri-dashboard-fill');
            },
            function () {
                $('#dashboard-icon').toggleClass('ri-dashboard-line ri-dashboard-fill');
            }
        );
    
        $('#calendar-icon, #calendar-link').hover(
            function () {
                $('#calendar-icon').toggleClass('ri-calendar-line ri-calendar-fill');
            },
            function () {
                $('#calendar-icon').toggleClass('ri-calendar-line ri-calendar-fill');
            }
        );
    
        // // Check if the link corresponds to the current page and add the 'active' class
        // if (window.location.href.includes("student")) {
        //     $("#team-link").addClass("active");
        // }
    
    
    });




