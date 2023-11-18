
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

    //^ Keep style on pages ---------------------

    $(document).ready(function() {
        // Récupérer le chemin de la page actuelle
        var path = window.location.pathname;
        path = path.replace(/\/$/, "");
        path = decodeURIComponent(path);
        
      
        // Parcourir chaque lien du menu
        $('#nav-links a').each(function() {
          var href = $(this).attr('href');
          // Vérifier si le lien correspond à la page actuelle
          if (path === href) {
            // Ajouter la classe "active" à l'élément parent (li)
            $(this).addClass('activemenu');
            var iconElement = $(this).prev('i');
            $(iconElement).addClass('activeIcon');

          }
        });
      });


    //^ change color label when focus on input ---------------------
    $(document).ready(function () {
        console.log("test")
        $('.inputs').on('focus',function() {
            console.log("click")
            var label = $(this).prev('label'); 
            console.log(label)
            $(label).toggleClass('labelclicked');
            var input = $(this);
            $(input).toggleClass('inputChange')
        })
        $('.inputs').on('blur',function() {
            console.log("click")
            var label = $(this).prev('label'); 
            console.log(label)
            $(label).toggleClass('labelclicked');
            var input = $(this);
            $(input).toggleClass('inputChange')
        })
    });


    //^ show/ hide sessions ---------------------

    $(document).ready(function () {
            
        $('#currentUpBtn').click(function () {
            console.log("ok")
                $('#current-session-list').slideToggle();
                $('#currentUpBtn').toggleClass('rotated');
            },
            
        );

        $('#upcomingUpBtn').click(function () {
            console.log("ok")
                $('#upcoming-session-list').slideToggle();
                $('#upcomingUpBtn').toggleClass('rotated');
            },
            
        );

    });

    // lazy loading past sessions

    $(document).ready(function () {
        // Lorsque le bouton est cliqué
        $('#upcomingUpBtn').click(function () {
            // Vérifier si les sessions à venir n'ont pas déjà été chargées
            if (!$('#upcoming-session-list').hasClass('sessions-loaded')) {
                // Charger les sessions à venir
                $.ajax({
                    url: '/', // Remplacez par votre URL de chargement des données
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Manipuler les données JSON (par exemple, les ajouter à la page)
                    displayUpcomingSessions(data);
    
                        // Ajouter une classe pour indiquer que les sessions à venir ont été chargées
                        $('#upcoming-session-list').addClass('sessions-loaded');
                    },
                    error: function (error) {
                        console.error('Erreur lors du chargement des sessions à venir :', error);
                    }
                });
            }
    
            // Afficher ou masquer les sessions à venir en fonction de l'état actuel
            $('#upcoming-session-list').slideToggle();
        });
    });