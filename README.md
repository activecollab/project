## Creating a new project

Use Composer to create a new project based on this template:

```bash
composer create-project activecollab/project Warhorse '0.10.*' --repository='{"type":"vcs","url":"https://github.com/activecollab/project"}'
```

This command will create a project with name Warhorse from a template. Post clone, it does several things:

1. Configures `composer.json` for your project
1. Adjusts application name and namespace (from `ActiveCollab\App` to `ActiveCollab\Warhorse`)
1. Adjusts environment variable names (prefix is changed from `APP_` to `WARHORSE_`)
1. Replace this `README.md` with `README.project.md` file
