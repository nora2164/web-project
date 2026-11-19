#!/bin/bash
# End-to-end tests (33 checks) against a temporary SQLite database.
# Usage: bash tests/e2e.sh   (needs PHP with pdo_sqlite, and curl)
set -u
B=http://127.0.0.1:8099
J=/tmp/jar.txt; J2=/tmp/jar2.txt; rm -f $J $J2 /tmp/bs.sqlite
cd "$(dirname "$0")/.."
DB_DRIVER=sqlite DB_PATH=/tmp/bs.sqlite php -S 127.0.0.1:8099 >/tmp/php.log 2>&1 &
PID=$!; sleep 1
pass=0; fail=0
check(){ if eval "$2"; then echo "PASS $1"; pass=$((pass+1)); else echo "FAIL $1"; fail=$((fail+1)); fi; }
tok(){ curl -s -b $1 -c $1 "$B/$2" | grep -o 'name="csrf_token" value="[^"]*"' | head -1 | sed 's/.*value="//;s/"//'; }

check "setup" "curl -s $B/setup.php | grep -q 'اكتمل التثبيت'"
check "setup idempotent" "curl -s $B/setup.php | grep -q 'موجودة مسبقاً'"
check "index lists books" "curl -s $B/index.php | grep -q 'العادات الذرية'"
check "search by author" "curl -s -G $B/summaries.php --data-urlencode 'q=مانسون' | grep -q 'فن اللامبالاة'"
check "search excludes others" "! curl -s -G $B/summaries.php --data-urlencode 'q=مانسون' | grep -q 'أربعون'"
check "search no results" "curl -s -G $B/summaries.php --data-urlencode 'q=zzzz' | grep -q 'لا توجد نتائج'"
check "search wildcard literal" "curl -s -G $B/summaries.php --data-urlencode 'q=%' | grep -q 'لا توجد نتائج'"
check "XSS in search escaped" "! curl -s -G $B/summaries.php --data-urlencode 'q=<script>alert(1)</script>' | grep -q '<script>alert'"
check "category filter" "curl -s -G $B/summaries.php --data-urlencode 'category=ديني' | grep -q 'أربعون'"
check "categories counts" "curl -s $B/categories.php | grep -q '3 ملخصات'"
check "favorites requires login" "curl -s -o /dev/null -w '%{redirect_url}' $B/favorites.php | grep -q 'login.php'"

T=$(tok $J register.php)
check "register" "curl -s -b $J -c $J -o /dev/null -w '%{redirect_url}' -d csrf_token=$T -d name=Nora -d email=nora@test.com -d password=secret123 -d password_confirm=secret123 -d next=index.php $B/register.php | grep -q index.php"
check "logged in greeting" "curl -s -b $J -c $J $B/index.php | grep -q 'Nora'"
T=$(tok $J register.php)
T=$(tok $J summary.php?id=1)
check "add favorite" "curl -s -b $J -c $J -o /dev/null -d csrf_token=$T -d book_id=1 -d return=favorites.php $B/add_favorite.php; curl -s -b $J $B/favorites.php | grep -q 'العادات الذرية'"
curl -s -b $J -c $J -o /dev/null -d csrf_token=$T -d book_id=2 -d return=favorites.php $B/add_favorite.php
check "add favorite w/o csrf blocked" "curl -s -b $J -c $J -o /dev/null -d book_id=3 $B/add_favorite.php; ! curl -s -b $J $B/favorites.php | grep -q 'قوة التفكير'"
check "rate book" "curl -s -b $J -c $J -o /dev/null -d csrf_token=$T -d book_id=1 -d rating=4 $B/add_rating.php; curl -s -b $J $B/summary.php?id=1 | grep -q 'تقييمك الحالي: <strong>4'"
check "re-rate updates" "curl -s -b $J -c $J -o /dev/null -d csrf_token=$T -d book_id=1 -d rating=2 $B/add_rating.php; curl -s -b $J $B/summary.php?id=1 | grep -q '(1 تقييم)'"
check "invalid rating rejected" "curl -s -b $J -c $J -o /dev/null -d csrf_token=$T -d book_id=1 -d rating=9 $B/add_rating.php; curl -s -b $J $B/summary.php?id=1 | grep -q 'تقييم غير صالح'"
FIDS=$(sqlite3 /tmp/bs.sqlite 'select id from favorites order by display_order' 2>/dev/null || php -r '$p=new PDO("sqlite:/tmp/bs.sqlite");echo implode(" ",$p->query("select id from favorites order by display_order")->fetchAll(PDO::FETCH_COLUMN));')
set -- $FIDS
check "reorder favorites" "curl -s -b $J -H 'Content-Type: application/json' -H \"X-CSRF-Token: $T\" -d '{\"order\":[{\"favorite_id\":$2,\"display_order\":1},{\"favorite_id\":$1,\"display_order\":2}]}' $B/update_favorite_order.php | grep -q '\"success\":true'"
check "reorder persisted" "curl -s -b $J $B/favorites.php | tr -d '\n' | grep -q 'الأب الغني.*العادات الذرية'"
check "reorder bad csrf" "curl -s -b $J -H 'X-CSRF-Token: bad' -d '{}' $B/update_favorite_order.php | grep -q '\"success\":false'"

# second user can't see or change the first user's favorites
T2=$(tok $J2 register.php)
curl -s -b $J2 -c $J2 -o /dev/null -d csrf_token=$T2 -d name=Sara -d email=sara@test.com -d password=secret123 -d password_confirm=secret123 $B/register.php
J3=/tmp/jar3.txt; rm -f $J3; T3=$(tok $J3 register.php)
check "duplicate email rejected" "curl -s -b $J3 -c $J3 -d csrf_token=$T3 -d name=X -d email=nora@test.com -d password=secret123 -d password_confirm=secret123 $B/register.php | grep -q 'مسجّل مسبقاً'"
check "favorites are per-user" "curl -s -b $J2 $B/favorites.php | grep -q 'لا توجد ملخصات في المفضلة'"
T2=$(tok $J2 index.php)
curl -s -b $J2 -H 'Content-Type: application/json' -H "X-CSRF-Token: $T2" -d "{\"order\":[{\"favorite_id\":$1,\"display_order\":99}]}" $B/update_favorite_order.php >/dev/null
check "can't reorder others' favorites" "curl -s -b $J $B/favorites.php | tr -d '\n' | grep -q 'الأب الغني.*العادات الذرية'"

check "remove favorite" "curl -s -b $J -c $J -o /dev/null -d csrf_token=$T -d book_id=1 -d return=favorites.php $B/remove_favorite.php; ! curl -s -b $J $B/favorites.php | grep -q 'العادات الذرية'"
check "open redirect blocked" "curl -s -b $J -c $J -o /dev/null -w '%{redirect_url}' -d csrf_token=$T -d book_id=3 -d return=https://evil.com $B/add_favorite.php | grep -qv evil"
check "logout" "curl -s -b $J -c $J -o /dev/null -d csrf_token=$T $B/logout.php; ! curl -s -b $J $B/index.php | grep -q 'Nora'"
T=$(tok $J login.php)
check "wrong password" "curl -s -b $J -c $J -d csrf_token=$T -d email=nora@test.com -d password=wrongpass $B/login.php | grep -q 'غير صحيحة'"
check "login + next" "curl -s -b $J -c $J -o /dev/null -w '%{redirect_url}' -d csrf_token=$T -d email=NORA@test.com -d password=secret123 -d next=favorites.php $B/login.php | grep -q favorites.php"
T=$(tok $J contact.php)
check "contact validation" "curl -s -b $J -c $J -d csrf_token=$T -d name=x -d email=bad -d subject=inquiry -d message=hi $B/contact.php | grep -q 'البريد الإلكتروني غير صحيح'"
check "contact saved" "curl -s -b $J -c $J -L -d csrf_token=$T -d name=Nora -d email=n@t.com -d subject=inquiry -d message='رسالة تجريبية طويلة' $B/contact.php | grep -q 'وصلتنا رسالتك'"
check "about live stats" "curl -s $B/about.php | grep -q 'قارئ مسجّل'"
check "no php errors" "! grep -iE 'warning|fatal|notice|deprecated' /tmp/php.log"
kill $PID
echo "== $pass passed, $fail failed"
