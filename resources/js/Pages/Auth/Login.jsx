import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <>
            <Head title="Sign in" />
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
            <link
                href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap"
                rel="stylesheet"
            />
            <link
                rel="stylesheet"
                href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
            />

            <style>{`
                *, *::before, *::after { box-sizing: border-box; }
                body, #app, #app > div { margin: 0; padding: 0; background: #0b1d24; }

                :root {
                    --auth-ink: #0f172a;
                    --auth-muted: #5b6472;
                    --auth-bg: #f4f7f8;
                    --auth-card: #ffffff;
                    --auth-border: #e2e8f0;
                    --auth-accent: #1f7a8c;
                    --auth-accent-strong: #16606d;
                    --auth-warm: #f2a65a;
                }

                .auth-shell {
                    min-height: 100vh;
                    display: flex;
                    font-family: "Space Grotesk", "Source Sans 3", sans-serif;
                    color: var(--auth-ink);
                }

                .auth-aside {
                    width: 40%;
                    min-height: 100vh;
                    background: linear-gradient(155deg, #0b2230 0%, #0f2b3a 55%, #0a1b24 100%);
                    color: #e2e8f0;
                    padding: 3.5rem 3rem;
                    position: relative;
                    overflow: hidden;
                }

                .auth-aside::before {
                    content: "";
                    position: absolute;
                    inset: 0;
                    background:
                        radial-gradient(ellipse 75% 55% at 15% -10%, rgba(31,122,140,0.45) 0%, transparent 60%),
                        radial-gradient(ellipse 55% 50% at 95% 110%, rgba(242,166,90,0.2) 0%, transparent 60%);
                    pointer-events: none;
                }

                .auth-brand {
                    display: flex;
                    align-items: center;
                    gap: 0.65rem;
                    text-decoration: none;
                    color: #fff;
                    margin-bottom: 2.6rem;
                }

                .auth-brand-icon {
                    width: 42px;
                    height: 42px;
                    border-radius: 12px;
                    background: linear-gradient(135deg, var(--auth-accent), var(--auth-accent-strong));
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    color: #fff;
                    font-size: 1.1rem;
                    box-shadow: 0 6px 18px rgba(31,122,140,0.45);
                }

                .auth-brand-text {
                    font-weight: 800;
                    letter-spacing: -0.02em;
                    font-size: 1.15rem;
                }

                .auth-brand-text span {
                    color: var(--auth-warm);
                }

                .auth-eyebrow {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 0.3rem 0.9rem;
                    border-radius: 999px;
                    background: rgba(255,255,255,0.08);
                    border: 1px solid rgba(255,255,255,0.18);
                    font-size: 0.7rem;
                    letter-spacing: 0.14em;
                    text-transform: uppercase;
                    font-weight: 700;
                    color: var(--auth-warm);
                }

                .auth-dot {
                    width: 6px;
                    height: 6px;
                    border-radius: 50%;
                    background: #22c55e;
                    box-shadow: 0 0 0 3px rgba(34,197,94,0.2);
                }

                .auth-aside h1 {
                    font-size: clamp(2rem, 3vw, 2.7rem);
                    font-weight: 800;
                    line-height: 1.1;
                    margin: 1.2rem 0 1rem;
                    color: #fff;
                }

                .auth-aside h1 em {
                    font-style: normal;
                    color: var(--auth-warm);
                }

                .auth-aside p {
                    color: rgba(226,232,240,0.7);
                    font-size: 0.95rem;
                    line-height: 1.7;
                    max-width: 320px;
                }

                .auth-features {
                    margin-top: 2rem;
                    display: grid;
                    gap: 0.75rem;
                }

                .auth-feature {
                    display: flex;
                    align-items: center;
                    gap: 0.8rem;
                    padding: 0.75rem 1rem;
                    border-radius: 12px;
                    background: rgba(255,255,255,0.07);
                    border: 1px solid rgba(255,255,255,0.1);
                    font-size: 0.82rem;
                    color: rgba(226,232,240,0.75);
                }

                .auth-feature i {
                    color: var(--auth-warm);
                }

                .auth-main {
                    flex: 1;
                    background: var(--auth-bg);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 2.5rem;
                }

                .auth-card {
                    width: 100%;
                    max-width: 460px;
                    background: var(--auth-card);
                    border-radius: 20px;
                    border: 1px solid var(--auth-border);
                    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
                    padding: 2.2rem;
                }

                .auth-head h2 {
                    font-size: 1.7rem;
                    font-weight: 800;
                    margin-bottom: 0.4rem;
                }

                .auth-head p {
                    color: var(--auth-muted);
                    font-size: 0.92rem;
                    margin-bottom: 1.4rem;
                }

                .auth-status {
                    background: rgba(34,197,94,0.12);
                    border: 1px solid rgba(34,197,94,0.2);
                    color: #166534;
                    font-weight: 600;
                    padding: 0.65rem 0.9rem;
                    border-radius: 12px;
                    margin-bottom: 1rem;
                    font-size: 0.85rem;
                }

                .auth-field {
                    margin-bottom: 1.1rem;
                }

                .auth-label {
                    font-size: 0.8rem !important;
                    text-transform: uppercase;
                    letter-spacing: 0.12em;
                    color: var(--auth-muted) !important;
                }

                .auth-input {
                    width: 100%;
                    border-radius: 12px !important;
                    border: 1.5px solid var(--auth-border) !important;
                    padding: 0.75rem 0.9rem !important;
                    font-size: 0.95rem;
                }

                .auth-input:focus {
                    border-color: var(--auth-accent) !important;
                    box-shadow: 0 0 0 3px rgba(31,122,140,0.15) !important;
                }

                .auth-checkbox {
                    border-radius: 6px !important;
                    border-color: var(--auth-border) !important;
                }

                .auth-actions {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-top: 1.2rem;
                }

                .auth-link {
                    font-size: 0.82rem;
                    color: var(--auth-accent);
                    font-weight: 700;
                    text-decoration: none;
                }

                .auth-link:hover {
                    text-decoration: underline;
                }

                .auth-submit {
                    width: 100%;
                    margin-top: 1.2rem;
                    padding: 0.85rem 1rem;
                    border: none;
                    border-radius: 12px;
                    background: linear-gradient(135deg, var(--auth-accent), var(--auth-accent-strong));
                    color: #fff;
                    font-size: 0.95rem;
                    font-weight: 800;
                    letter-spacing: 0.08em;
                    text-transform: uppercase;
                    cursor: pointer;
                    box-shadow: 0 10px 24px rgba(31,122,140,0.35);
                    transition: all 0.2s ease;
                }

                .auth-submit:disabled {
                    opacity: 0.6;
                    cursor: not-allowed;
                    box-shadow: none;
                }

                .auth-submit:hover:not(:disabled) {
                    transform: translateY(-2px);
                }

                .auth-footer {
                    margin-top: 1.4rem;
                    font-size: 0.85rem;
                    color: var(--auth-muted);
                    text-align: center;
                }

                .auth-footer a {
                    color: var(--auth-accent);
                    font-weight: 700;
                    text-decoration: none;
                }

                .auth-footer a:hover {
                    text-decoration: underline;
                }

                @media (max-width: 980px) {
                    .auth-shell { flex-direction: column; }
                    .auth-aside { width: 100%; min-height: auto; padding: 2.5rem 2rem; }
                    .auth-main { padding: 2rem 1.5rem; }
                    .auth-features { display: none; }
                }

                @media (max-width: 520px) {
                    .auth-card { padding: 1.8rem; }
                }
            `}</style>

            <div className="auth-shell">
                <aside className="auth-aside">
                    <a className="auth-brand" href="/">
                        <span className="auth-brand-icon"><i className="bi bi-book-half"></i></span>
                        <span className="auth-brand-text">Book<span>Hub</span></span>
                    </a>

                    <div className="auth-eyebrow">
                        <span className="auth-dot"></span>
                        Storefront access
                    </div>

                    <h1>Welcome back to your <em>bookstore</em> dashboard</h1>
                    <p>Manage inventory, respond to customers, and track your orders in one focused workspace.</p>

                    <div className="auth-features">
                        <div className="auth-feature">
                            <i className="bi bi-chat-dots"></i>
                            Real-time customer conversations
                        </div>
                        <div className="auth-feature">
                            <i className="bi bi-box-seam"></i>
                            Inventory and order insights
                        </div>
                        <div className="auth-feature">
                            <i className="bi bi-shield-check"></i>
                            Secure admin controls
                        </div>
                    </div>
                </aside>

                <main className="auth-main">
                    <div className="auth-card">
                        <div className="auth-head">
                            <h2>Sign in</h2>
                            <p>Use your email and password to continue.</p>
                        </div>

                        {status && <div className="auth-status">{status}</div>}

                        <form onSubmit={submit}>
                            <div className="auth-field">
                                <InputLabel htmlFor="email" value="Email" className="auth-label" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    name="email"
                                    value={data.email}
                                    className="mt-1 block w-full auth-input"
                                    autoComplete="username"
                                    isFocused={true}
                                    onChange={(e) => setData('email', e.target.value)}
                                />
                                <InputError message={errors.email} className="mt-2" />
                            </div>

                            <div className="auth-field">
                                <InputLabel htmlFor="password" value="Password" className="auth-label" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    name="password"
                                    value={data.password}
                                    className="mt-1 block w-full auth-input"
                                    autoComplete="current-password"
                                    onChange={(e) => setData('password', e.target.value)}
                                />
                                <InputError message={errors.password} className="mt-2" />
                            </div>

                            <div className="auth-actions">
                                <label className="flex items-center gap-2 text-sm">
                                    <Checkbox
                                        name="remember"
                                        checked={data.remember}
                                        className="auth-checkbox"
                                        onChange={(e) => setData('remember', e.target.checked)}
                                    />
                                    <span>Remember me</span>
                                </label>

                                {canResetPassword && (
                                    <Link href={route('password.request')} className="auth-link">
                                        Forgot password?
                                    </Link>
                                )}
                            </div>

                            <button className="auth-submit" disabled={processing} type="submit">
                                {processing ? 'Signing in...' : 'Sign in'}
                            </button>

                            <div className="auth-footer">
                                New here?
                                <Link href={route('register')}> Create an account</Link>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        </>
    );
}
