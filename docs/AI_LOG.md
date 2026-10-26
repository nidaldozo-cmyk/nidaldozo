# AI Log

## Milestone 1

### Tools and tasks
- Log 1: **Tool/model:** Gemini (model unspecified)
- **Task:** Set up the webpage/repository structure.
- **Main prompt:** “i am a bit lost here, can you clarify the important parts for me required to make a repository that has all of the stuff needed”
- **Generated:** Repository structure including agents and the backend/frontend folders and subfolders.
- **Kept/changed/rejected:** Kept the agents and backend/frontend folders and subfolders.

- Log 2: **Tool/model:** Claude (specific model/version not provided)
- **Task:** Git and GitHub implementation; cloning the project from one PC to another.
- **Main prompt:** “how to create a repo on github”; “error remote origin already exists”; “fatal invalid milestone1”; “failed to push some refs”; “it branched but not all files are in the branch, im missing frontend and backend”.
- **Generated:** Git commands and explanations.
- **Kept/changed/rejected:** Used the commands successfully. Rejected the instruction to leave “Add a README” unchecked because no README existed; created `README.md` manually. Files changed during the process because of the `.gitkeep` files.

### Where the AI was wrong
- **What was wrong:** Initially advised leaving “Add a README” unchecked on GitHub because files already existed locally, but there was no README.
- **How it was noticed:** Checked VS Code and saw no README.
- **How it was fixed:** Created `README.md` manually.

- Log 3: **Tool/model:** Claude (model/version unknown)
- **Task:** Create an ERD diagram based on a reference image made with diagrams.net.
- **Main prompt:** “can  you create the diagram with it lookking like this” (with the reference image attached).
- **Generated:** A diagram in diagrams.net, which was screenshotted and saved as `docs/ERD.png`, as well as another generated version.
- **Kept/changed/rejected:** Kept the screenshot saved as `docs/ERD.png`; did not use the other generated version.

- Log 4: **Tool/model:** Copilot (model unspecified)
- **Task:** Configure the FitPulse background image to load on the first page visit and fade in whenever the home page is opened.
- **Main prompt:** “make the fitpulse image load only when i first open the web page, when im cliclink from anywhere else to it dont make it load, make the image always load”
- **Generated:** Added a persistent eager image preload and set the home hero fade animation to run every time it is rendered; updated the cache versions.
- **Kept/changed/rejected:** Kept the persistent image load and clarified that the image should always fade in, not only on its first load.
- **Where AI was wrong:** No.

- Log 5: **Tool/model:** Claude for prompts (specific model/version not provided); Copilot for code generation (specific model/version not provided).
- **Task:** Iteratively create, style, correct, and refine the FitPulse frontend pages and interactions.
- **Main prompt:** “Create frontend/index.html for FitPulse, a fitness tracker. Use Bootstrap 5 and jQuery from CDN. Include a responsive navbar with links to #dashboard, #workouts, #exercises, #goals, #profile, #login and #register, an empty <main id="app-content"> container and a footer. Link css/style.css and js/app.js. Do not create other files.” Later prompts specified a jQuery hash router and individual login, register, dashboard, workouts, exercises, goals, and profile views. The user also asked, “what could i to to make the site seem more full” and “how can i make the profile section more stylish”. The detailed prompt sequence and work summary are in [AI_LOG_FRONTEND_WORK.txt](./AI_LOG_FRONTEND_WORK.txt).
- **Generated:** Created and refined the SPA shell, routing, page views, and shared styling. Iterative changes covered page backgrounds, navigation and transitions, home-page content, responsive card/table/progress styling, Profile presentation and avatar controls, and removal of redundant frontend CSS and unused classes.
- **Kept/changed/rejected:** Kept the requested pages and controls, while adjusting styling and spacing across follow-up iterations to match the user's preferences. Changed the Profile presentation after the user clarified it should not have a background image.

### Where the AI was wrong
- **What was wrong:** The other version Claude generated looked messy.
- **How it was noticed:** Reviewed the generated version.
- **How it was fixed:** Did not use that version.
- **What was wrong:** The Profile section retained a background when the user wanted no background.
- **How it was noticed:** The user reviewed the page and pointed out that it did not match the request.
- **How it was fixed:** The user clarified the desired appearance in follow-up prompts, and the Profile section was corrected.

FITPULSE FRONTEND WORK — AI LOG DETAIL
======================================

Purpose
-------
This plain-text note gives a fuller account of the AI-assisted work on the
FitPulse frontend. It supplements the entry in AI_LOG.md and can be opened in
Notepad.

Tools
-----
Prompts: Claude (specific model/version not provided by the user).
Code generation and editing: Copilot (specific model/version not provided).

The user described the collaboration as using Claude for prompts and Copilot
for generation. This note does not guess model versions.

Task scope
----------
The work covered iterative setup, creation, styling, correction, and refinement
of the FitPulse website frontend: its single-page shell and navigation, page
views, visual design, and selected browser-side interactions. The user gave
initial structured build instructions and then continued with requests to tune
the design and fix mismatches. This was frontend work; this summary does not
claim that the static sample page content is connected to a production
database or backend.

Initial prompt sequence
-----------------------
The user supplied a multi-step prompt sequence. The opening shell-page prompt
was:

"Create frontend/index.html for FitPulse, a fitness tracker. Use Bootstrap 5
and jQuery from CDN. Include a responsive navbar with links to #dashboard,
#workouts, #exercises, #goals, #profile, #login and #register, an empty
<main id="app-content"> container and a footer. Link css/style.css and
js/app.js. Do not create other files."

The router-and-styles prompt asked for a jQuery SPA router that reads and
validates the hash route, loads the matching HTML view into #app-content,
shows a Bootstrap alert on load failure, marks the active navigation link,
and creates shared CSS. It also instructed that index.html not be changed in
that step.

The user then requested separate Bootstrap view fragments:
- Login: email and password form, Login button, and link to Register.
- Register: name, email, password, confirmation, Register button, and link
  to Login.
- Dashboard: four sample stat cards, recent-workout table, and two goal
  progress bars.
- Workouts: workout table, New workout modal form, and logged-sets table.
- Exercises: search form, exercise cards and badges, and a clearly marked
  future external-API placeholder.
- Goals: goal cards with progress, target and deadline, plus a New goal modal.
- Profile: profile details and account settings form.

The user also supplied these later prompts:

"what could i to to make the site seem more full"
"how can i make the profile section more stylish"

The conversation continued with additional short requests to alter colors,
rounding, spacing, backgrounds, content placement, page styling, and behavior.
Their exact full wording is not reproduced here where it was not provided as
one complete prompt.

What was built and refined
--------------------------
1. SPA shell and navigation
   - Set up the shared frontend entry page, Bootstrap/jQuery dependencies,
     navigation links, and a main region where views are loaded.
   - Implemented hash-based navigation in app.js for Home, Login, Register,
     Dashboard, Workouts, Exercises, Goals, and Profile.
   - Added active-link state, invalid-route fallback, and a visible error
     message when a view cannot be loaded. The router removes script elements
     from loaded view responses before inserting the view.

2. Home page and shared appearance
   - Refined the home hero, feature cards, imagery, and motivational quote.
   - Adjusted background behavior so the shared app background is used on
     non-authentication routes while Login and Register retain their
     authentication background.
   - Tuned navigation, logos, responsive spacing, transitions, and card
     styling through follow-up requests.

3. Dashboard and feature pages
   - Restyled Dashboard cards, recent-workout table, and goal progress display.
   - Applied a coordinated visual treatment to Workouts, Exercises, and Goals,
     preserving their content and controls while improving their headers,
     cards, forms, tables, and progress bars.
   - Kept the New workout and New goal actions visually rounded.

4. Profile
   - Restyled the profile identity and account details/settings panels,
     repositioned membership information, and removed the requested stat
     cards.
   - Added a changeable profile avatar with initials as the fallback.
     Selected JPG, PNG, or WebP images are limited to 1 MB and stored in
     browser localStorage; the user can remove the stored picture.
   - The user wanted no background image in the Profile section. An earlier
     result did not follow that preference; the user pointed out the mismatch
     and it was corrected after follow-up prompts.

5. Redundant-code cleanup
   - During the final cleanup, removed unused view classes and animation
     overrides that canceled the corresponding animations.
   - Consolidated duplicated goal progress-bar declarations.
   - Preserved the rounded-action styling and bumped the stylesheet cache
     version so the updated CSS is loaded.

What the user kept, changed, or rejected
----------------------------------------
The user continued with the requested page structure and interactions and
iteratively guided the visual result. They requested changes to colors,
rounding, spacing, background use, section layout, and page styling. They
corrected results that did not reflect their preferences; in particular,
they clarified that Profile should not have a background image. Those
clarifications were applied rather than treating the first generated result
as final.

Where the AI was wrong
----------------------
The Profile section kept a background when the user wanted that section to
have no background. The user noticed the mismatch while reviewing the page
and corrected it with follow-up prompts. The page was then adjusted to match
the clarification. No other specific mistake is recorded here.

Validation recorded
-------------------
- Checked the updated app in the existing VS Code Live Server browser.
- Confirmed the Workouts and Goals views loaded and the New goal control
  retained its fully rounded appearance.
- Confirmed the home page rendered its three feature cards and motivational
  quote.
- Ran git diff --check after the redundant-code cleanup; it passed.

This document describes the work at a high level for the AI log. It is not a
claim that every form has a connected backend operation, nor a substitute for
the code itself.
