/*
 * فرستادن به سرور. آدرس پایهٔ API در VITE_API_URL تنظیم می‌شود.
 * تا وقتی تنظیم نشده، درخواست فرستاده نمی‌شود و { local: true } برمی‌گردد
 * تا صفحه صادقانه بگوید داده فعلاً فقط روی همین دستگاه مانده است.
 */
export async function post(path, body) {
  const base = import.meta.env.VITE_API_URL;
  if (!base) {
    await new Promise((r) => setTimeout(r, 500));
    return { local: true };
  }
  const res = await fetch(base.replace(/\/$/, '') + path, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body)
  });
  if (!res.ok) throw new Error(String(res.status));
  return res.json().catch(() => ({}));
}
