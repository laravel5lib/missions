# How to Contribute to this Project

## General Guidelines

1. Missions.Me uses an agile approach to project development.
2. We follow the *Gitflow Workflow* when contributing to this project.
3. An issue with the appropiate labels should be created for new features, bug fixes, or enhancements.
4. Issue's should be referenced by commits and/or pull requests.
5. Commit history should be kept clean by prefering `git rebase` over `git merge`.
6. All code must have passing tests.

## The Agile Approach with GitHub

| Agile          | GitHub                                   |
| -------------- | ---------------------------------------- |
| Stories        | Issues                                   |
| Sprint         | Milestone                                |
| Global Backlog | Open, Unmilestoned and Unassigned Issues |
| Sprint Backlog | Open, Milestoned and Unassigned Issues   |

| Global Backlog | Sprint Backlog | In Progress | In Review    | Closed |
| -------------- | -------------- | ----------- | ------------ | ------ |
| open           | open           | open        | open         | closed |
| unmilestoned   | milestoned     | milestoned  | milestoned   |        |
| unassigned     | unassigned     | assigned    | assigned     |        |
|                |                |             | pull request |        |

## How Gitflow Works

The Gitflow Workflow uses a central repository as the communication hub for all developers. And, as in other workflows, developers work locally and push branches to the central repo. The only difference is the branch structure of the project.

### Historical Branches

Instead of a single master branch, this workflow uses two branches to record the history of the project. The master branch stores the official release history, and the develop branch serves as an integration branch for features. It's also convenient to tag all commits in the master branch with a version number.

(image here)

The rest of this workflow revolves around the distinction between these two branches.

### Feature Branches

Each new feature should reside in its own branch, which can be pushed to the central repository for backup/collaboration. But, instead of branching off of master, feature branches use develop as their parent branch. When a feature is complete, it gets merged back into develop. **Features should never interact directly with master.**

(image here)

Note that feature branches combined with the develop branch is, for all intents and purposes, the Feature Branch Workflow. But, the Gitflow Workflow doesn’t stop there.

### Release Branches

(image here)

Once develop has acquired enough features for a release (or a predetermined release date is approaching), you fork a release branch off of develop. Creating this branch starts the next release cycle, so no new features can be added after this point—only bug fixes, documentation generation, and other release-oriented tasks should go in this branch. Once it's ready to ship, the release gets merged into master and tagged with a version number. In addition, it should be merged back into develop, which may have progressed since the release was initiated.

Using a dedicated branch to prepare releases makes it possible for one team to polish the current release while another team continues working on features for the next release. It also creates well-defined phases of development (e.g., it's easy to say, “this week we're preparing for version 4.0” and to actually see it in the structure of the repository).

Common conventions:

- branch off: develop
- merge into: master
- naming convention: release-* or release/*

### Maintenance Branches

(image here)

Maintenance or “hotfix” branches are used to quickly patch production releases. This is the only branch that should fork directly off of master. As soon as the fix is complete, it should be merged into both master and develop (or the current release branch), and master should be tagged with an updated version number.

Having a dedicated line of development for bug fixes lets your team address issues without interrupting the rest of the workflow or waiting for the next release cycle. You can think of maintenance branches as ad hoc release branches that work directly with master.

*Gitflow information taken from [https://www.atlassian.com/git/tutorials/comparing-workflows#gitflow-workflow](https://www.atlassian.com/git/tutorials/comparing-workflows#gitflow-workflow)*

## Labeling Issues

[https://help.github.com/articles/applying-labels-to-issues-and-pull-requests/](https://help.github.com/articles/applying-labels-to-issues-and-pull-requests/)

## Referencing Issues in Commits and Pull requests

Issue numbers should be referenced in commit messages and pull request descriptions.

### Closing Issues using Keywords

[https://help.github.com/articles/closing-issues-using-keywords/](https://help.github.com/articles/closing-issues-using-keywords/)

### Commit Guidelines

Keep commits small. Try to limit a commit to a single change or group of related changes.

**Commit Template**
From: [https://git-scm.com/book/en/v2/Distributed-Git-Contributing-to-a-Project](https://git-scm.com/book/en/v2/Distributed-Git-Contributing-to-a-Project)

```
Short (50 chars or less) summary of changes

More detailed explanatory text, if necessary.  Wrap it to
about 72 characters or so.  In some contexts, the first
line is treated as the subject of an email and the rest of
the text as the body.  The blank line separating the
summary from the body is critical (unless you omit the body
entirely); tools like rebase can get confused if you run
the two together.

Further paragraphs come after blank lines.

  - Bullet points are okay, too

  - Typically a hyphen or asterisk is used for the bullet,
    preceded by a single space, with blank lines in
    between, but conventions vary here
```

We want our commit history to tell a story about the progess of a contribution.

**Merging vs. Rebasing**

[https://www.atlassian.com/git/tutorials/merging-vs-rebasing](https://www.atlassian.com/git/tutorials/merging-vs-rebasing)
