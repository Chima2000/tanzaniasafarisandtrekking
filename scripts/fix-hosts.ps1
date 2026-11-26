#Requires -RunAsAdministrator
$hostsPath = 'C:\Windows\System32\drivers\etc\hosts'
$content = Get-Content -Path $hostsPath -Raw

# Fix the corrupted McAfee + jerryadventures glued line
$content = $content -replace 'mssplus\.mcafee\.com127\.0\.0\.1 jerryadventures\.test', "mssplus.mcafee.com`r`n"
$content = $content -replace '(?m)^127\.0\.0\.1\s+jerryadventures\.test\r?\n?', ''

# Insert inside Herd block if possible
if ($content -match '(# End Herd generated Hosts)') {
    if ($content -notmatch '(?m)^127\.0\.0\.1\s+jerryadventures\.test\s*$') {
        $content = $content -replace '# End Herd generated Hosts', "127.0.0.1 jerryadventures.test`r`n# End Herd generated Hosts"
    }
} else {
    if (-not $content.EndsWith("`n")) {
        $content += "`r`n"
    }
    $content += "127.0.0.1 jerryadventures.test`r`n"
}

Set-Content -Path $hostsPath -Value $content -Encoding ascii -NoNewline
Write-Output 'Hosts file updated for jerryadventures.test'
