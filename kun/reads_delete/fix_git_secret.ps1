# Fix Git Secret Leak - Remove sensitive files from Git history

Write-Host "🔒 Fixing Git Secret Leak..." -ForegroundColor Yellow
Write-Host ""

# Step 1: Remove the file from all commits
Write-Host "Step 1: Removing sensitive files from Git history..." -ForegroundColor Cyan

# Use git filter-branch to remove the file from all commits
git filter-branch --force --index-filter `
  "git rm --cached --ignore-unmatch kun/GET_SUPABASE_KEYS.md" `
  --prune-empty --tag-name-filter cat -- --all

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Successfully removed from history" -ForegroundColor Green
} else {
    Write-Host "❌ Failed to remove from history" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "Step 2: Force pushing to origin..." -ForegroundColor Cyan
Write-Host "⚠️  This will rewrite history on GitHub!" -ForegroundColor Yellow
Write-Host ""

$confirm = Read-Host "Do you want to force push? (yes/no)"
if ($confirm -eq "yes") {
    git push origin --force --all
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Successfully pushed to GitHub" -ForegroundColor Green
    } else {
        Write-Host "❌ Failed to push" -ForegroundColor Red
    }
} else {
    Write-Host "⏸️  Skipped force push. Run manually: git push origin --force --all" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Step 3: Cleanup..." -ForegroundColor Cyan
git reflog expire --expire=now --all
git gc --prune=now --aggressive

Write-Host ""
Write-Host "✅ Done! Your secret has been removed from Git history." -ForegroundColor Green
Write-Host ""
Write-Host "⚠️  IMPORTANT: You should REGENERATE your Supabase keys since they were exposed!" -ForegroundColor Red
Write-Host "Go to: https://supabase.com/dashboard/project/payjcwtxciyvlkhzdcjc/settings/api" -ForegroundColor Yellow
