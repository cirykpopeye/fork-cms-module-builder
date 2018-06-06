# Builder module for Fork CMS [WIP]
> The builder module allows you to build forms dynamically and gives you the possiblity to add them as widgets to the frontend.

## Features
- Add fields, such as text, textarea, editors, ... (more will be added)
- Add sections, a section consist of multiple fields. F.e. an article contains a title, summary and the actual content.
- Add nodes, a node is the actual content and uses the section as a template. F.e. a node is "My first article", using the "Article" section.
- Nest nodes by selecting a parent
- All data from the nodes will be automagically available in the frontend, child nodes will also be available.
- The section `keys` are used to select which template will be used, if not found it'll use the default.

## What currently works
- Everything listed above

## What currently does not work
- Add image as a field