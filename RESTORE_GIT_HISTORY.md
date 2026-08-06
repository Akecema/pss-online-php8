# Restoring full git history

`PSS_Online_git_history.bundle` in this folder contains the complete commit
history from this migration (every change, one commit per logical step,
with descriptive messages). To turn this folder into a proper git
repository with that history, open a terminal in this folder and run:

    git clone PSS_Online_git_history.bundle .git_temp
    rm -rf .git_temp/.git  # not needed, clone already extracted history
    git init
    git remote add bundle PSS_Online_git_history.bundle
    git fetch bundle
    git reset --hard bundle/master

(or more simply, if you just want to inspect history without turning this
folder into a live repo: `git clone PSS_Online_git_history.bundle
pss-online-history` elsewhere, and browse it there with `git log`, `git
show <hash>`, etc.)

You can then delete `PSS_Online_git_history.bundle` and this file once
history is restored/reviewed.
