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
