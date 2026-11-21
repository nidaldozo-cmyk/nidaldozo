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
  let externalExerciseSearchRequest = null;
  let externalExerciseSearchId = 0;

  function getExternalExerciseErrorMessage(xhr, responseData) {
    const payload = responseData && typeof responseData === "object" ? responseData : xhr.responseJSON;
    if (payload && typeof payload.error === "string" && payload.error.trim() !== "") {
      return payload.error;
    }

    if (xhr.status === 0) {
      return "Unable to reach the exercise search service. Please try again.";
    }

    if (xhr.status === 200) {
      return "The exercise search service returned an invalid response.";
    }

    return `Exercise search failed (HTTP ${xhr.status}).`;
  }

  function showExternalExerciseMessage($results, message, isError) {
    $results.empty().attr("aria-busy", "false");
    $("<div>", {
      class: isError ? "col-12 alert alert-danger mb-0" : "col-12 mb-0 text-body-secondary",
      role: isError ? "alert" : "status",
    })
      .text(message)
      .appendTo($results);
  }

  function getSafeExternalImageUrl(image) {
    if (typeof image !== "string" || image.trim() === "") {
      return null;
    }

    const imageUrl = document.createElement("a");
    imageUrl.href = image.trim();
    return imageUrl.protocol === "http:" || imageUrl.protocol === "https:" ? imageUrl.href : null;
  }

  function createExternalExerciseImagePlaceholder(name) {
    return $("<div>", {
      class: "d-flex align-items-center justify-content-center bg-body-tertiary text-body-secondary",
      role: "img",
      "aria-label": `${name}: image unavailable`,
    }).text("Image unavailable");
  }

  function renderExternalExerciseCard(exercise) {
    const item = exercise && typeof exercise === "object" ? exercise : {};
    const name = typeof item.name === "string" && item.name.trim() !== "" ? item.name : "Unknown exercise";
    const muscleGroup =
      typeof item.muscle_group === "string" && item.muscle_group.trim() !== ""
        ? item.muscle_group
        : "Not specified";
    const equipment =
      typeof item.equipment === "string" && item.equipment.trim() !== "" ? item.equipment : "Not specified";
    const $card = $("<article>", { class: "card h-100 fitpulse-exercise-card" });
    const imageUrl = getSafeExternalImageUrl(item.image);
    const $imageContainer = $("<div>", { class: "ratio ratio-16x9" });

    if (imageUrl) {
      const $image = $("<img>", {
        class: "card-img-top",
        alt: `${name} exercise`,
        loading: "lazy",
      }).attr("src", imageUrl);
      $image.on("error", function () {
        $(this).replaceWith(createExternalExerciseImagePlaceholder(name));
      });
      $image.appendTo($imageContainer);
    } else {
      $imageContainer.append(createExternalExerciseImagePlaceholder(name));
    }

    $card.append($imageContainer);
    const $body = $("<div>", { class: "card-body" });
    $("<h3>", { class: "h5 card-title" }).text(name).appendTo($body);
    const $details = $("<div>", { class: "d-flex flex-wrap gap-2" });
    $("<span>", { class: "badge text-bg-secondary" }).text(muscleGroup).appendTo($details);
    $("<span>", { class: "badge text-bg-light border" }).text(equipment).appendTo($details);
    $body.append($details);
    $card.append($body);

    return $("<div>", { class: "col-12 col-sm-6 col-lg-4" }).append($card);
  }

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
      url: `views/${view}.html?v=19`,
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
  $(document).on("submit", "#external-exercise-search-form", function (event) {
    event.preventDefault();

    const search = String($(this).find("#exercise-search").val() || "").trim();
    const $results = $("#external-exercise-results");
    const $submitButton = $(this).find("button[type='submit']");
    if (!$results.length) {
      return;
    }

    const requestId = ++externalExerciseSearchId;
    if (externalExerciseSearchRequest) {
      externalExerciseSearchRequest.abort();
      externalExerciseSearchRequest = null;
    }

    if (Array.from(search).length < 2) {
      $submitButton.prop("disabled", false);
      showExternalExerciseMessage($results, "Minimum 2 characters required.", true);
      return;
    }

    $results.empty().attr("aria-busy", "true");
    const $loading = $("<div>", {
      class: "col-12 d-flex align-items-center gap-2",
      role: "status",
    });
    $("<span>", {
      class: "spinner-border spinner-border-sm",
      "aria-hidden": "true",
    }).appendTo($loading);
    $("<span>").text("Searching external exercises...").appendTo($loading);
    $loading.appendTo($results);
    $submitButton.prop("disabled", true);

    externalExerciseSearchRequest = $.ajax({
      url: `/nidaldozo/backend/external/exercises?search=${encodeURIComponent(search)}`,
      method: "GET",
      dataType: "json",
    })
      .done((data, textStatus, xhr) => {
        if (requestId !== externalExerciseSearchId) {
          return;
        }

        externalExerciseSearchRequest = null;
        $submitButton.prop("disabled", false);

        if (xhr.status !== 200 || !Array.isArray(data)) {
          showExternalExerciseMessage(
            $results,
            getExternalExerciseErrorMessage(xhr, data),
            true,
          );
          return;
        }

        if (data.length === 0) {
          showExternalExerciseMessage($results, "No exercises found. Try a different search.", false);
          return;
        }

        $results.empty().attr("aria-busy", "false");
        data.forEach((exercise) => $results.append(renderExternalExerciseCard(exercise)));
      })
      .fail((xhr) => {
        if (requestId !== externalExerciseSearchId) {
          return;
        }

        externalExerciseSearchRequest = null;
        $submitButton.prop("disabled", false);
        showExternalExerciseMessage(
          $results,
          getExternalExerciseErrorMessage(xhr, xhr.responseJSON),
          true,
        );
      });
  });
});
