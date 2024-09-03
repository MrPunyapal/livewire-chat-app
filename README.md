# livewire-chat-app with Laravel Reverb
## Timeline

### 31st Aug
- Created a new Laravel project #initialCommit with Breeze
- Installed broadcasting with `php artisan install:broadcasting` to enable broadcasting functionality
- Added pint configuration check [here](/pint.json)
- Integrated rector with support for Laravel-rector  
- Implemented phpstan with support for larastan with bleeding edge support
- Replaced volt with traditional livewire components

### 1st Sep
- Adjusted layouts to work with livewire full page components
- Added pest type coverage to ensure full type safety
- Removed email verification as it is not needed in our case
- Optimized tests for livewire components
- Expanded test coverage to reach 100%
- Created Room model, migration, factory, and tests
- Created Member model, migration, factory, and tests
- Created Chat model, migration, factory, and tests
- Established relationships between Room, Member, and Chat, and added tests for them

### 2nd Sep
- Moved dashboard and Profile components to pages directory
- Added Profile Attribute to User model and updated tests
- Created Chats component and added tests
- Refactored web.php to use middleware group for auth routes
- designed the sidebar and added the sidebar component
- Added tests for the sidebar component with and without rooms

### 3rd Sep

- We got our first PR #1

More updates to come... 

