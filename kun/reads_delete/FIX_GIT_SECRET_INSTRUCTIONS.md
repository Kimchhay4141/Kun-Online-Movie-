# 🔒 Fix Git Secret Leak - Step by Step

## Problem
GitHub blocked your push because `GET_SUPABASE_KEYS.md` contains your Supabase secret key in commit `f114389`.

## Solution Options

### Option 1: Interactive Rebase (Recommended - Clean History)

```bash
# 1. Start interactive rebase from before the problematic commit
git rebase -i 158cf7c

# 2. In the editor that opens, change 'pick' to 'edit' for commit f114389
#    It will look like:
#    pick f114389 done update code
#    pick ba0d224 done
#
#    Change to:
#    edit f114389 done update code
#    pick ba0d224 done

# 3. Save and close the editor

# 4. Remove the file
git rm kun/GET_SUPABASE_KEYS.md

# 5. Amend the commit
git commit --amend --no-edit

# 6. Continue the rebase
git rebase --continue

# 7. Force push (this rewrites history)
git push origin main --force
```

### Option 2: Use git filter-repo (Faster for multiple files)

```bash
# 1. Install git-filter-repo (if not installed)
# On Windows with pip:
pip install git-filter-repo

# 2. Remove the file from all history
git filter-repo --path kun/GET_SUPABASE_KEYS.md --invert-paths

# 3. Force push
git push origin main --force
```

### Option 3: Allow the Secret on GitHub (NOT Recommended)

GitHub gives you a link to allow the secret:
https://github.com/Kimchhay4141/Kun-Online-Movie-/security/secret-scanning/unblock-secret/3IECMeFgKnGMt1kaWngGu33U9ij

**⚠️ WARNING**: This leaves your secret exposed in Git history!

## Easiest Solution for You

Since you only have 2 commits ahead of origin (f114389 and ba0d224), the easiest is:

```bash
# 1. Reset to origin/main (this removes your 2 local commits)
git reset --hard origin/main

# 2. Re-apply your changes WITHOUT the sensitive files
# Your changes are already in the working directory
git add .

# 3. Make sure .gitignore is updated
git add .gitignore

# 4. Create a new commit WITHOUT the documentation files
git commit -m "Update upload services and fix Supabase integration"

# 5. Push normally (no force needed)
git push origin main
```

## After Fixing Git

### ⚠️ IMPORTANT: Rotate Your Supabase Keys!

Your keys were exposed in Git history. You MUST regenerate them:

1. Go to: https://supabase.com/dashboard/project/payjcwtxciyvlkhzdcjc/settings/api

2. Click "Reset" on both keys:
   - Reset the **anon key**
   - Reset the **service_role key**

3. Update your `.env` file with the new keys:
   ```env
   SUPABASE_ANON_KEY=NEW_KEY_HERE
   SUPABASE_SERVICE_ROLE_KEY=NEW_KEY_HERE
   SUPABASE_KEY=${SUPABASE_SERVICE_ROLE_KEY}
   ```

4. Clear config cache:
   ```bash
   php artisan config:clear
   ```

5. Test your uploads still work:
   ```bash
   php test_upload_v2.php
   ```

## What Happened?

1. You committed `GET_SUPABASE_KEYS.md` which contained example keys
2. GitHub scanned your commit and found what looks like a Supabase secret
3. GitHub blocked the push to protect you

## Prevention

The updated `.gitignore` now includes:
```
GET_SUPABASE_KEYS.md
SUPABASE_*.md
FIX_*.md
*_FIXED.md
*_COMPLETE.md
test_*.php
check_*.php
verify_*.php
migrate_*.php
```

These files won't be committed again.

---

## Quick Fix Command (Copy-Paste)

```bash
# Reset to before the problematic commits
git reset --hard origin/main

# Your changes are still in working directory
# Add everything except sensitive files (gitignore handles this)
git add .

# Commit with a clean message
git commit -m "Update upload services for Supabase integration"

# Push normally
git push origin main
```

Then **regenerate your Supabase keys immediately**!
