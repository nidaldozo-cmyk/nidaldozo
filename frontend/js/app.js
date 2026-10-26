$(function () {
  const routes = new Set([
    "home",
    "login",
    "register",
    "dashboard",
    "workouts",
    "exercises",
    "goals",
    "profile",
  ]);
  const profileAvatarStorageKey = "fitpulse.profile.avatar";
  const maxProfileAvatarSize = 1024 * 1024;
  let currentRoute = "home";

  function setProfileAvatar($appContent, imageData) {
    const $avatar = $appContent.find(".fitpulse-profile-avatar");
    $avatar.find("img").remove();
    $avatar.find(".fitpulse-profile-avatar-initials").toggle(!imageData);
    $avatar.attr("role", imageData ? null : "img");
    $avatar.attr("aria-label", imageData ? null : "Alex Morgan initials");

    if (imageData) {
      $("<img>", {
        src: imageData,
        alt: "Alex Morgan profile picture",
      }).appendTo($avatar);
      $appContent.find("#profile-avatar-remove").removeClass("d-none");
    } else {
      $appContent.find("#profile-avatar-remove").addClass("d-none");
    }
  }

  function restoreProfileAvatar($appContent) {
    try {
      setProfileAvatar($appContent, window.localStorage.getItem(profileAvatarStorageKey));
    } catch (error) {
      $appContent.find("#profile-avatar-status").text("Unable to load the saved profile picture in this browser.");
      console.error("Unable to load the saved profile picture.", error);
    }
  }

  function saveProfileAvatar(file, $appContent) {
    const $status = $appContent.find("#profile-avatar-status");
    const allowedTypes = ["image/jpeg", "image/png", "image/webp"];

    if (!allowedTypes.includes(file.type)) {
      $status.text("Choose a JPG, PNG, or WebP image.");
      return;
    }

    if (file.size > maxProfileAvatarSize) {
      $status.text("Choose an image smaller than 1 MB.");
      return;
    }

    const reader = new FileReader();
    reader.addEventListener("load", () => {
      if (typeof reader.result !== "string") {
        $status.text("Unable to read that image. Please try another file.");
        return;
      }

      try {
        window.localStorage.setItem(profileAvatarStorageKey, reader.result);
        setProfileAvatar($appContent, reader.result);
        $status.text("Profile picture updated.");
      } catch (error) {
        $status.text("Unable to save the profile picture in this browser.");
        console.error("Unable to save the profile picture.", error);
      }
    });
    reader.addEventListener("error", () => {
      $status.text("Unable to read that image. Please try another file.");
      console.error("Unable to read the selected profile picture.", reader.error);
    });
    reader.readAsDataURL(file);
  }

  function renderRoute() {
    const requestedRoute = window.location.hash.slice(1) || "home";
    const route = routes.has(requestedRoute) ? requestedRoute : "home";
    const view = route === "home" ? "fitpulse" : route;
    const isSwitchingAuthPages =
      (currentRoute === "login" && route === "register") ||
      (currentRoute === "register" && route === "login");
    const isEnteringAuthFromHome = currentRoute === "home" && (route === "login" || route === "register");

    currentRoute = route;
    const isAuthPage = route === "login" || route === "register";
    $("body").toggleClass("auth-background", isAuthPage);
    $("body").toggleClass("home-page", route === "home");
    $("body").toggleClass("app-background", !isAuthPage);
    const $appContent = $("#app-content");
    $appContent.toggleClass("auth-switch-delay", isSwitchingAuthPages);
    $appContent.toggleClass("auth-entry-delay", isEnteringAuthFromHome);
    const $routeLinks = $(".navbar a[href^='#']");

    $routeLinks.each(function () {
      const isActive = this.hash === `#${route}`;
      $(this).toggleClass("active", isActive);

      if (isActive) {
        $(this).attr("aria-current", "page");
      } else {
        $(this).removeAttr("aria-current");
      }
    });

    $.ajax({
      url: `views/${view}.html?v=15`,
      dataType: "html",
    })
      .done((response) => {
        if (route !== currentRoute) {
          return;
        }

        const parsedView = new DOMParser().parseFromString(response, "text/html");
        $(parsedView.body).find("script").remove();
        $appContent.empty().append(Array.from(parsedView.body.childNodes));

        if (route === "profile") {
          restoreProfileAvatar($appContent);
        }
      })
      .fail(() => {
        if (route !== currentRoute) {
          return;
        }

        $appContent.empty().append(
          $("<div>", {
            class: "alert alert-danger m-3",
            role: "alert",
            text: `Unable to load the ${route} page. Please try again later.`,
          }),
        );
      });
  }

  renderRoute();
  $(window).on("hashchange", renderRoute);
  $(document).on("change", "#profile-avatar-input", function () {
    const file = this.files && this.files[0];
    if (file) {
      saveProfileAvatar(file, $("#app-content"));
    }
  });
  $(document).on("click", "#profile-avatar-remove", function () {
    try {
      window.localStorage.removeItem(profileAvatarStorageKey);
      setProfileAvatar($("#app-content"), null);
      $("#profile-avatar-status").text("Profile picture removed.");
    } catch (error) {
      $("#profile-avatar-status").text("Unable to remove the saved profile picture in this browser.");
      console.error("Unable to remove the saved profile picture.", error);
    }
  });
});
