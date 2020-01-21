# PackWiz

PackWiz is a Full-Stack web application designed and developed as a final
project for the "COMP 10065 - PHP & JavaScript" course at Mohawk College. It
utilizes a range of technologies from the course including PHP/PDO, JavaScript,
JQuery, AJAX/fetch requests, and MySQL.

The following text is taken from the project index and explains the main
application concepts.

## About

### What is this?

PackWiz is an application for storing, retrieving, and managing collections of files on the web.

Users manage collections or "packs" of files through PackWiz. Files can be
uploaded by registered users and shared to anyone with the link. Packs are
managed through the web interface and may be downloaded as a single zip
file.

### What can I do with it?

PackWiz has a number of uses including:

- A personal file storage locker
- Transferring files between devices
- Sharing files with friends
- File backups

### How does it work?

**Packs vs Files** - Logged-in users have access to a File Library and Pack
Library. Files can be uploaded to your file library and packs are created by
entering a unique name. Files from your library can be included or excluded
from a pack via the Edit Pack page.

Once a pack has been created and files have been added it can be downloaded as
a single zip archive from the Download or Download Preview pages. These page
URLs uniquely depend on the pack name, owning user and pack settings, and are
accessible from the Pack Library page in your account. Individual files from a
pack may also be downloaded from the Download Preview page.

**Packs Contain Files** - Packs contain any combination of files uploaded to
the File Library. Files can be included or excluded from a pack individually
with checkbox controls from the Edit Pack page. A file from your File Library
can be present in any number of packs at once.

**Public Packs** - Packs can be made "public" on creation.  Public packs are
listed on the "Public Packs" in the format *username/packname*. Files in public
packs are downloadable and discoverable by anyone.

**Private Packs** - Packs not marked "public" are considered private. Private
packs are unlisted except within your account, and require knowledge of a
secret key in order to be downloaded. For private packs, the secret key is
embedded in the Download and Download Preview page links.

### What are its limitations?
- Packs and files have single owners and cannot be managed by multiple user accounts
- An uploaded file cannot be more than ~4GB or it will be truncated automatically
- Files cannot be downloaded until they are added to at least one pack
- Pack and file names must be unique, you cannot have more than one file or pack with a shared name
- Once a pack has been created it cannot be renamed and settings cannot be changed. To alter a pack delete and re-create it with the correct settings
