<style>
.back-link { display: inline-flex; align-items: center; gap: .5rem; color: var(--text-secondary); text-decoration: none; margin-bottom: 1rem; }
.back-link:hover { color: #fff; }
.user-form { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.75rem; margin-top: 1.25rem; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; }
.field { display: flex; flex-direction: column; gap: .45rem; }
.field.full { grid-column: 1 / -1; }
.field label { font-weight: 600; font-size: .85rem; color: var(--text-secondary); }
.field input, .field select, .field textarea { background: var(--light-bg); border: 1px solid var(--border-color); color: #fff; border-radius: 10px; padding: .75rem .9rem; }
.field input:focus, .field select:focus, .field textarea:focus { outline: none; border-color: var(--primary-color); }
.error { color: var(--danger-color); font-size: .8rem; }
.form-actions { display: flex; gap: .75rem; margin-top: 1.5rem; }
.btn-add { display: inline-flex; align-items: center; gap: .5rem; padding: .7rem 1.25rem; background: var(--primary-color); color: #fff; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; text-decoration: none; }
.btn-reset { display: inline-flex; align-items: center; padding: .7rem 1.1rem; background: var(--light-bg); color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: 10px; text-decoration: none; font-weight: 600; }
.section-label { margin: 1.5rem 0 .75rem; font-size: 1rem; }
.perm-group { background: var(--light-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 1rem; margin-bottom: 1rem; }
.perm-group h4 { margin-bottom: .75rem; font-size: .9rem; color: var(--text-secondary); text-transform: capitalize; }
.perm-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: .6rem; }
.check { display: flex; gap: .6rem; align-items: flex-start; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 10px; padding: .7rem; cursor: pointer; }
.check input { margin-top: .2rem; accent-color: var(--primary-color); }
.check strong { display: block; font-size: .85rem; }
.check small { color: var(--text-muted); font-size: .75rem; }
.muted { color: var(--text-muted); }
@media (max-width: 700px) { .form-grid { grid-template-columns: 1fr; } }
</style>
