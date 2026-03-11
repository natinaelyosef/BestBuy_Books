import { useState, useEffect } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        account_type: 'customer',
        terms: false,
    });

    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);
    const [passwordStrength, setPasswordStrength] = useState({ percentage: 0, text: '', color: 'rgba(91,76,255,0.15)' });
    const [fieldErrors, setFieldErrors] = useState({ name: '', email: '', password: '', password_confirmation: '', terms: '' });
    const [formProgress, setFormProgress] = useState(1);

    useEffect(() => {
        if (data.password) {
            let strength = 0;
            if (data.password.length >= 8) strength++;
            if (data.password.length >= 12) strength++;
            if (/[a-z]/.test(data.password)) strength++;
            if (/[A-Z]/.test(data.password)) strength++;
            if (/[0-9]/.test(data.password)) strength++;
            if (/[^a-zA-Z0-9]/.test(data.password)) strength++;
            const pct = Math.min((strength / 6) * 100, 100);
            if (strength <= 2) setPasswordStrength({ percentage: pct, text: 'Weak', color: '#ff4d6d' });
            else if (strength <= 4) setPasswordStrength({ percentage: pct, text: 'Fair', color: '#f5b042' });
            else setPasswordStrength({ percentage: pct, text: 'Strong', color: '#00c98b' });
        } else {
            setPasswordStrength({ percentage: 0, text: '', color: 'rgba(91,76,255,0.15)' });
        }
    }, [data.password]);

    useEffect(() => {
        const fields = ['name', 'email', 'password', 'password_confirmation'];
        const filled = fields.filter(f => data[f] && data[f].trim() !== '').length;
        setFormProgress(Math.min(Math.ceil((filled / fields.length) * 3), 3) || 1);
    }, [data]);

    const validateName = (v) => {
        if (!/^[a-zA-Z0-9_]{3,20}$/.test(v)) {
            setFieldErrors(p => ({ ...p, name: 'Username must be 3–20 characters: letters, numbers, underscores only' }));
            return false;
        }
        setFieldErrors(p => ({ ...p, name: '' }));
        return true;
    };

    const validateEmail = (v) => {
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) {
            setFieldErrors(p => ({ ...p, email: 'Please enter a valid email address' }));
            return false;
        }
        setFieldErrors(p => ({ ...p, email: '' }));
        return true;
    };

    const validatePassword = (v) => {
        if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(v)) {
            setFieldErrors(p => ({ ...p, password: 'Min 8 chars with uppercase, lowercase & number' }));
            return false;
        }
        setFieldErrors(p => ({ ...p, password: '' }));
        return true;
    };

    const validateMatch = (pw, cpw) => {
        if (pw !== cpw) {
            setFieldErrors(p => ({ ...p, password_confirmation: 'Passwords do not match' }));
            return false;
        }
        setFieldErrors(p => ({ ...p, password_confirmation: '' }));
        return true;
    };

    const submit = (e) => {
        e.preventDefault();
        const ok = [
            validateName(data.name),
            validateEmail(data.email),
            validatePassword(data.password),
            validateMatch(data.password, data.password_confirmation),
        ].every(Boolean);
        if (!data.terms) {
            setFieldErrors(p => ({ ...p, terms: 'You must agree to the Terms & Privacy Policy' }));
            return;
        }
        if (ok) post(route('register'), { onFinish: () => reset('password', 'password_confirmation') });
    };

    const fieldState = (field) => {
        if (!data[field]) return '';
        return fieldErrors[field] ? 'err' : 'ok';
    };

    const benefits = [
        { icon: 'bi-book-half',        text: 'Access thousands of books across all partner stores' },
        { icon: 'bi-arrow-repeat',     text: 'Rent affordably or buy to own — your choice' },
        { icon: 'bi-shield-check',     text: 'Bank-level security & encrypted checkout' },
        { icon: 'bi-bell',             text: 'Personalized recommendations & reading lists' },
        { icon: 'bi-headset',          text: '24/7 customer support, always here for you' },
        { icon: 'bi-phone',            text: 'Read on any device, anywhere, anytime' },
    ];

    return (
        <>
            <Head title="Create Account — BookHub" />
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

            <style>{`
                *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

                /* ── full-screen override — kills GuestLayout chrome ── */
                body, #app, #app > div { margin: 0 !important; padding: 0 !important; background: #0c0a1a !important; }

                .rp-root {
                    font-family: 'Outfit', system-ui, sans-serif;
                    display: flex;
                    min-height: 100vh;
                    width: 100%;
                    -webkit-font-smoothing: antialiased;
                }

                /* ═══════════════════════════
                   LEFT PANEL
                ═══════════════════════════ */
                .rp-left {
                    width: 42%;
                    min-height: 100vh;
                    background: linear-gradient(155deg, #0f1a3a 0%, #1a1050 48%, #0d1030 100%);
                    position: relative;
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    padding: 3.5rem 3rem;
                }

                .rp-left::before {
                    content: '';
                    position: absolute; inset: 0; pointer-events: none;
                    background:
                        radial-gradient(ellipse 80% 60% at 10% -10%, rgba(91,76,255,0.55) 0%, transparent 55%),
                        radial-gradient(ellipse 55% 50% at 90% 110%, rgba(245,176,66,0.22) 0%, transparent 55%);
                }

                .rp-ring {
                    position: absolute; border-radius: 50%; pointer-events: none;
                    border: 1px solid rgba(255,255,255,0.06);
                }
                .rp-ring-1 { width: 480px; height: 480px; top: -180px; right: -180px; }
                .rp-ring-2 { width: 300px; height: 300px; bottom: -120px; left: -80px; border-color: rgba(245,176,66,0.07); }
                .rp-ring-3 { width: 160px; height: 160px; top: 38%; left: 6%; border-color: rgba(91,76,255,0.2); }

                .rp-left-inner { position: relative; z-index: 1; }

                .rp-brand {
                    display: flex; align-items: center; gap: 0.65rem;
                    text-decoration: none; margin-bottom: 2.8rem;
                }
                .rp-brand-icon {
                    width: 42px; height: 42px; border-radius: 12px;
                    background: linear-gradient(135deg, #5b4cff 0%, #3d2fe0 100%);
                    display: flex; align-items: center; justify-content: center;
                    font-size: 1.1rem; color: #fff;
                    box-shadow: 0 4px 18px rgba(91,76,255,0.45);
                }
                .rp-brand-text {
                    font-size: 1.15rem; font-weight: 800;
                    color: #fff; letter-spacing: -0.02em;
                }
                .rp-brand-text span { color: #f5b042; }

                .rp-eyebrow {
                    display: inline-flex; align-items: center; gap: 0.5rem;
                    padding: 0.3rem 0.9rem; border-radius: 999px;
                    background: rgba(255,255,255,0.10); border: 1px solid rgba(255,255,255,0.18);
                    font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
                    letter-spacing: 1.1px; color: #f5b042;
                    margin-bottom: 1.2rem;
                }
                .rp-dot {
                    width: 6px; height: 6px; border-radius: 50%; background: #00c98b;
                    box-shadow: 0 0 0 3px rgba(0,201,139,0.25);
                    animation: blink 2s ease-in-out infinite;
                }
                @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

                .rp-left h1 {
                    font-size: clamp(1.9rem, 3vw, 2.8rem); font-weight: 900;
                    color: #fff; line-height: 1.1; letter-spacing: -0.035em;
                    margin-bottom: 1rem;
                }
                .rp-left h1 em { font-style: normal; color: #f5b042; }

                .rp-left > .rp-left-inner > p {
                    font-size: 0.95rem; color: rgba(255,255,255,0.5);
                    line-height: 1.7; margin-bottom: 2.5rem; max-width: 340px;
                }

                .rp-benefits-title {
                    font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
                    letter-spacing: 1.2px; color: rgba(255,255,255,0.4);
                    margin-bottom: 1rem;
                }

                .rp-benefits { display: flex; flex-direction: column; gap: 0.7rem; }

                .rp-benefit {
                    display: flex; align-items: center; gap: 0.85rem;
                    padding: 0.75rem 1rem; border-radius: 12px;
                    background: rgba(255,255,255,0.06);
                    border: 1px solid rgba(255,255,255,0.09);
                    backdrop-filter: blur(6px);
                    transition: background 200ms;
                }
                .rp-benefit:hover { background: rgba(255,255,255,0.10); }

                .rp-benefit-icon {
                    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
                    background: rgba(91,76,255,0.25); border: 1px solid rgba(91,76,255,0.35);
                    display: flex; align-items: center; justify-content: center;
                    font-size: 0.95rem; color: #9b8fff;
                }
                .rp-benefit span {
                    font-size: 0.82rem; color: rgba(255,255,255,0.65);
                    font-weight: 500; line-height: 1.4;
                }

                /* ═══════════════════════════
                   RIGHT PANEL
                ═══════════════════════════ */
                .rp-right {
                    flex: 1;
                    background: #f5f4ff;
                    min-height: 100vh;
                    display: flex;
                    flex-direction: column;
                    overflow-y: auto;
                }

                .rp-right-top {
                    display: flex; align-items: center; justify-content: space-between;
                    padding: 1.4rem 2.5rem;
                    border-bottom: 1px solid rgba(91,76,255,0.1);
                    background: rgba(255,255,255,0.7);
                    backdrop-filter: blur(12px);
                    position: sticky; top: 0; z-index: 10;
                }

                .rp-back {
                    display: flex; align-items: center; gap: 0.45rem;
                    font-size: 0.82rem; font-weight: 700; color: #4e4a64;
                    text-decoration: none; padding: 0.42rem 0.9rem;
                    border-radius: 999px; border: 1.5px solid rgba(91,76,255,0.18);
                    background: rgba(255,255,255,0.8);
                    transition: all 180ms;
                }
                .rp-back:hover { color: #5b4cff; border-color: #5b4cff; background: rgba(91,76,255,0.06); }
                .rp-back i { font-size: 0.85rem; }

                .rp-top-login {
                    font-size: 0.82rem; color: #8a86a0;
                }
                .rp-top-login a {
                    color: #5b4cff; font-weight: 700; text-decoration: none; margin-left: 0.3rem;
                }
                .rp-top-login a:hover { text-decoration: underline; }

                .rp-form-wrap {
                    flex: 1;
                    display: flex; align-items: flex-start; justify-content: center;
                    padding: 2.5rem 2.5rem 3rem;
                }

                .rp-form-inner {
                    width: 100%; max-width: 520px;
                }

                /* heading */
                .rp-form-head { margin-bottom: 1.8rem; }
                .rp-form-head h2 {
                    font-size: clamp(1.55rem, 2.5vw, 2rem); font-weight: 900;
                    color: #14102b; letter-spacing: -0.03em; margin-bottom: 0.35rem;
                }
                .rp-form-head p { font-size: 0.87rem; color: #8a86a0; }

                /* progress */
                .rp-progress {
                    display: flex; align-items: center;
                    gap: 0; margin-bottom: 2rem;
                }
                .rp-step {
                    display: flex; flex-direction: column; align-items: center; gap: 0.35rem;
                    flex: 1; position: relative;
                }
                .rp-step::after {
                    content: '';
                    position: absolute; top: 16px; left: 50%; right: -50%;
                    height: 2px; background: rgba(91,76,255,0.15); z-index: 0;
                }
                .rp-step:last-child::after { display: none; }
                .rp-step.active::after { background: rgba(91,76,255,0.4); }

                .rp-step-circle {
                    width: 32px; height: 32px; border-radius: 50%; z-index: 1;
                    background: rgba(91,76,255,0.12); border: 2px solid rgba(91,76,255,0.2);
                    color: #8a86a0; font-size: 0.8rem; font-weight: 800;
                    display: flex; align-items: center; justify-content: center;
                    transition: all 260ms;
                }
                .rp-step.active .rp-step-circle {
                    background: #5b4cff; border-color: #5b4cff;
                    color: #fff; box-shadow: 0 4px 14px rgba(91,76,255,0.4);
                }
                .rp-step-label {
                    font-size: 0.72rem; font-weight: 700;
                    color: #8a86a0; letter-spacing: 0.3px;
                }
                .rp-step.active .rp-step-label { color: #5b4cff; }

                /* server error banner */
                .rp-errors {
                    margin-bottom: 1.25rem;
                    display: flex; flex-direction: column; gap: 0.5rem;
                }
                .rp-err-item {
                    display: flex; align-items: center; gap: 0.55rem;
                    padding: 0.65rem 0.9rem; border-radius: 10px;
                    background: #fff0f3; border: 1.5px solid rgba(255,77,109,0.25);
                    font-size: 0.8rem; color: #a0143a; font-weight: 600;
                }
                .rp-err-item i { color: #ff4d6d; flex-shrink: 0; }

                /* form fields */
                .rp-field { margin-bottom: 1.2rem; }
                .rp-label {
                    display: block; font-size: 0.78rem; font-weight: 700;
                    color: #4e4a64; margin-bottom: 0.5rem; letter-spacing: 0.2px;
                }
                .rp-input-wrap { position: relative; }
                .rp-input-icon {
                    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
                    color: #b0aac8; font-size: 0.95rem; pointer-events: none; z-index: 2;
                }
                .rp-input-wrap input,
                .rp-input-wrap select {
                    width: 100%;
                    padding: 0.8rem 1rem 0.8rem 2.6rem;
                    border: 1.5px solid rgba(91,76,255,0.18);
                    border-radius: 11px; font-family: 'Outfit', sans-serif;
                    font-size: 0.9rem; color: #14102b;
                    background: #fff;
                    outline: none;
                    transition: all 200ms;
                    -webkit-appearance: none;
                }
                .rp-input-wrap input:focus,
                .rp-input-wrap select:focus {
                    border-color: #5b4cff;
                    box-shadow: 0 0 0 3px rgba(91,76,255,0.12);
                }
                .rp-input-wrap input.ok { border-color: #00c98b; }
                .rp-input-wrap input.ok:focus { box-shadow: 0 0 0 3px rgba(0,201,139,0.12); }
                .rp-input-wrap input.err,
                .rp-input-wrap select.err { border-color: #ff4d6d; }
                .rp-input-wrap input.err:focus { box-shadow: 0 0 0 3px rgba(255,77,109,0.12); }
                .rp-input-wrap input::placeholder { color: #b0aac8; }

                .rp-eye-btn {
                    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
                    background: none; border: none; cursor: pointer;
                    color: #b0aac8; font-size: 0.9rem; padding: 4px; z-index: 3;
                    transition: color 160ms;
                }
                .rp-eye-btn:hover { color: #5b4cff; }

                .rp-field-err {
                    display: flex; align-items: center; gap: 0.35rem;
                    font-size: 0.75rem; color: #ff4d6d; font-weight: 600;
                    margin-top: 0.35rem;
                }
                .rp-field-err i { font-size: 0.7rem; }

                /* password strength */
                .rp-strength { display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem; }
                .rp-strength-bar {
                    flex: 1; height: 5px; border-radius: 999px;
                    background: rgba(91,76,255,0.1); overflow: hidden;
                }
                .rp-strength-fill {
                    height: 100%; border-radius: 999px;
                    transition: width 300ms ease, background-color 300ms ease;
                }
                .rp-strength-label {
                    font-size: 0.73rem; font-weight: 700;
                    min-width: 48px; text-align: right;
                }

                /* two-col row */
                .rp-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

                /* terms */
                .rp-terms {
                    display: flex; align-items: flex-start; gap: 0.75rem;
                    padding: 1rem; border-radius: 11px;
                    background: rgba(91,76,255,0.05);
                    border: 1.5px solid rgba(91,76,255,0.12);
                    margin-bottom: 1.4rem; cursor: pointer;
                    transition: border-color 200ms;
                }
                .rp-terms:has(input:checked) { border-color: #5b4cff; background: rgba(91,76,255,0.08); }
                .rp-terms input[type="checkbox"] {
                    width: 18px; height: 18px; border-radius: 5px; cursor: pointer;
                    accent-color: #5b4cff; flex-shrink: 0; margin-top: 2px;
                }
                .rp-terms-text {
                    font-size: 0.8rem; color: #4e4a64; line-height: 1.55;
                }
                .rp-terms-text a {
                    color: #5b4cff; font-weight: 700; text-decoration: none;
                }
                .rp-terms-text a:hover { text-decoration: underline; }

                /* submit button */
                .rp-submit {
                    width: 100%;
                    padding: 0.9rem 1.5rem;
                    background: linear-gradient(135deg, #5b4cff 0%, #3d2fe0 100%);
                    color: #fff; border: none; border-radius: 12px;
                    font-family: 'Outfit', sans-serif; font-size: 0.95rem; font-weight: 800;
                    cursor: pointer; letter-spacing: 0.2px;
                    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
                    transition: all 200ms;
                    box-shadow: 0 6px 22px rgba(91,76,255,0.35);
                    margin-bottom: 0;
                }
                .rp-submit:hover:not(:disabled) {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 30px rgba(91,76,255,0.45);
                }
                .rp-submit:active:not(:disabled) { transform: translateY(0); }
                .rp-submit:disabled { opacity: 0.65; cursor: not-allowed; }

                /* divider */
                .rp-divider {
                    display: flex; align-items: center; gap: 0.75rem;
                    margin: 1.2rem 0; color: #b0aac8; font-size: 0.75rem; font-weight: 600;
                }
                .rp-divider::before, .rp-divider::after {
                    content: ''; flex: 1; height: 1px; background: rgba(91,76,255,0.12);
                }

                .rp-login-cta {
                    text-align: center;
                    font-size: 0.85rem; color: #8a86a0;
                }
                .rp-login-cta a {
                    color: #5b4cff; font-weight: 700;
                    text-decoration: none; margin-left: 0.25rem;
                }
                .rp-login-cta a:hover { text-decoration: underline; }

                /* ═══════════════════════════
                   RESPONSIVE
                ═══════════════════════════ */
                @media (max-width: 900px) {
                    .rp-root { flex-direction: column; }
                    .rp-left { width: 100%; min-height: auto; padding: 2.5rem 2rem 2rem; }
                    .rp-left h1 { font-size: 1.8rem; }
                    .rp-benefits { display: none; }
                    .rp-right { min-height: auto; }
                    .rp-form-wrap { padding: 1.5rem 1.5rem 2.5rem; }
                    .rp-row { grid-template-columns: 1fr; }
                }
                @media (max-width: 480px) {
                    .rp-left { padding: 2rem 1.25rem 1.5rem; }
                    .rp-right-top { padding: 1rem 1.25rem; }
                    .rp-form-wrap { padding: 1.25rem 1.25rem 2rem; }
                }
            `}</style>

            <div className="rp-root">

                {/* ── LEFT ── */}
                <div className="rp-left">
                    <div className="rp-ring rp-ring-1"></div>
                    <div className="rp-ring rp-ring-2"></div>
                    <div className="rp-ring rp-ring-3"></div>

                    <div className="rp-left-inner">
                        <a className="rp-brand" href="/">
                            <div className="rp-brand-icon"><i className="bi bi-book-half"></i></div>
                            <span className="rp-brand-text">Book<span>Hub</span></span>
                        </a>

                        <div className="rp-eyebrow">
                            <span className="rp-dot"></span>
                            Join the community
                        </div>

                        <h1>Your Next <em>Great Read</em><br />Awaits You</h1>
                        <p>Create a free account and unlock thousands of books from multiple stores — rent affordably or buy to own, all in one place.</p>

                        <div className="rp-benefits-title">Why BookHub?</div>
                        <div className="rp-benefits">
                            {benefits.map((b, i) => (
                                <div className="rp-benefit" key={i}>
                                    <div className="rp-benefit-icon"><i className={`bi ${b.icon}`}></i></div>
                                    <span>{b.text}</span>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* ── RIGHT ── */}
                <div className="rp-right">

                    {/* sticky top bar */}
                    <div className="rp-right-top">
                        <Link href="/" className="rp-back">
                            <i className="bi bi-arrow-left"></i> Back to Home
                        </Link>
                        <div className="rp-top-login">
                            Already have an account?
                            <Link href={route('login')}>Sign in</Link>
                        </div>
                    </div>

                    <div className="rp-form-wrap">
                        <div className="rp-form-inner">

                            {/* heading */}
                            <div className="rp-form-head">
                                <h2>Create your account</h2>
                                <p>Fill in your details below to get started — it takes under a minute.</p>
                            </div>

                            {/* progress */}
                            <div className="rp-progress">
                                {['Account', 'Profile', 'Complete'].map((label, i) => (
                                    <div className={`rp-step ${formProgress >= i + 1 ? 'active' : ''}`} key={i}>
                                        <div className="rp-step-circle">
                                            {formProgress > i + 1
                                                ? <i className="bi bi-check-lg" style={{ fontSize: '0.8rem' }}></i>
                                                : i + 1}
                                        </div>
                                        <div className="rp-step-label">{label}</div>
                                    </div>
                                ))}
                            </div>

                            {/* server errors */}
                            {Object.keys(errors).length > 0 && (
                                <div className="rp-errors">
                                    {Object.entries(errors).map(([k, v]) => v && (
                                        <div key={k} className="rp-err-item">
                                            <i className="bi bi-exclamation-circle-fill"></i>
                                            {v}
                                        </div>
                                    ))}
                                </div>
                            )}

                            <form onSubmit={submit}>

                                {/* Username + Email row */}
                                <div className="rp-row">
                                    <div className="rp-field">
                                        <label className="rp-label" htmlFor="name">Username <span style={{color:'#ff4d6d'}}>*</span></label>
                                        <div className="rp-input-wrap">
                                            <i className="bi bi-person rp-input-icon"></i>
                                            <input
                                                id="name" type="text" name="name"
                                                value={data.name}
                                                className={fieldState('name')}
                                                placeholder="e.g. book_lover42"
                                                onChange={e => { setData('name', e.target.value); validateName(e.target.value); }}
                                                required
                                            />
                                        </div>
                                        {fieldErrors.name && (
                                            <div className="rp-field-err">
                                                <i className="bi bi-exclamation-circle-fill"></i>
                                                {fieldErrors.name}
                                            </div>
                                        )}
                                    </div>

                                    <div className="rp-field">
                                        <label className="rp-label" htmlFor="email">Email Address <span style={{color:'#ff4d6d'}}>*</span></label>
                                        <div className="rp-input-wrap">
                                            <i className="bi bi-envelope rp-input-icon"></i>
                                            <input
                                                id="email" type="email" name="email"
                                                value={data.email}
                                                className={fieldState('email')}
                                                placeholder="you@example.com"
                                                onChange={e => { setData('email', e.target.value); validateEmail(e.target.value); }}
                                                required
                                            />
                                        </div>
                                        {fieldErrors.email && (
                                            <div className="rp-field-err">
                                                <i className="bi bi-exclamation-circle-fill"></i>
                                                {fieldErrors.email}
                                            </div>
                                        )}
                                    </div>
                                </div>

                                {/* Account type */}
                                <div className="rp-field">
                                    <label className="rp-label" htmlFor="account_type">Account Type <span style={{color:'#ff4d6d'}}>*</span></label>
                                    <div className="rp-input-wrap">
                                        <i className="bi bi-person-badge rp-input-icon"></i>
                                        <select
                                            id="account_type" name="account_type"
                                            value={data.account_type}
                                            onChange={e => setData('account_type', e.target.value)}
                                            required
                                        >
                                            <option value="customer">Customer — Browse & Buy/Rent Books</option>
                                            <option value="store_owner">Store Owner — List & Sell Books</option>
                                        </select>
                                    </div>
                                </div>

                                {/* Password + Confirm row */}
                                <div className="rp-row">
                                    <div className="rp-field">
                                        <label className="rp-label" htmlFor="password">Password <span style={{color:'#ff4d6d'}}>*</span></label>
                                        <div className="rp-input-wrap">
                                            <i className="bi bi-lock rp-input-icon"></i>
                                            <input
                                                id="password"
                                                type={showPassword ? 'text' : 'password'}
                                                name="password"
                                                value={data.password}
                                                className={fieldState('password')}
                                                placeholder="Min 8 characters"
                                                onChange={e => {
                                                    setData('password', e.target.value);
                                                    validatePassword(e.target.value);
                                                    if (data.password_confirmation) validateMatch(e.target.value, data.password_confirmation);
                                                }}
                                                required
                                            />
                                            <button type="button" className="rp-eye-btn" onClick={() => setShowPassword(v => !v)}>
                                                <i className={`bi bi-${showPassword ? 'eye-slash' : 'eye'}`}></i>
                                            </button>
                                        </div>
                                        {data.password && (
                                            <div className="rp-strength">
                                                <div className="rp-strength-bar">
                                                    <div className="rp-strength-fill"
                                                        style={{ width: `${passwordStrength.percentage}%`, backgroundColor: passwordStrength.color }}>
                                                    </div>
                                                </div>
                                                <span className="rp-strength-label" style={{ color: passwordStrength.color }}>
                                                    {passwordStrength.text}
                                                </span>
                                            </div>
                                        )}
                                        {fieldErrors.password && (
                                            <div className="rp-field-err">
                                                <i className="bi bi-exclamation-circle-fill"></i>
                                                {fieldErrors.password}
                                            </div>
                                        )}
                                    </div>

                                    <div className="rp-field">
                                        <label className="rp-label" htmlFor="password_confirmation">Confirm Password <span style={{color:'#ff4d6d'}}>*</span></label>
                                        <div className="rp-input-wrap">
                                            <i className="bi bi-lock-fill rp-input-icon"></i>
                                            <input
                                                id="password_confirmation"
                                                type={showConfirmPassword ? 'text' : 'password'}
                                                name="password_confirmation"
                                                value={data.password_confirmation}
                                                className={fieldState('password_confirmation')}
                                                placeholder="Repeat your password"
                                                onChange={e => {
                                                    setData('password_confirmation', e.target.value);
                                                    validateMatch(data.password, e.target.value);
                                                }}
                                                required
                                            />
                                            <button type="button" className="rp-eye-btn" onClick={() => setShowConfirmPassword(v => !v)}>
                                                <i className={`bi bi-${showConfirmPassword ? 'eye-slash' : 'eye'}`}></i>
                                            </button>
                                        </div>
                                        {fieldErrors.password_confirmation && (
                                            <div className="rp-field-err">
                                                <i className="bi bi-exclamation-circle-fill"></i>
                                                {fieldErrors.password_confirmation}
                                            </div>
                                        )}
                                    </div>
                                </div>

                                {/* Terms */}
                                <label className="rp-terms">
                                    <input
                                        type="checkbox" id="terms" name="terms"
                                        checked={data.terms}
                                        onChange={e => {
                                            setData('terms', e.target.checked);
                                            setFieldErrors(p => ({ ...p, terms: e.target.checked ? '' : 'You must agree to continue' }));
                                        }}
                                    />
                                    <span className="rp-terms-text">
                                        I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>. 
                                        I understand my data will be processed in accordance with these policies.
                                    </span>
                                </label>
                                {fieldErrors.terms && (
                                    <div className="rp-field-err" style={{ marginTop: '-0.8rem', marginBottom: '1rem' }}>
                                        <i className="bi bi-exclamation-circle-fill"></i>
                                        {fieldErrors.terms}
                                    </div>
                                )}

                                {/* Submit */}
                                <button type="submit" className="rp-submit" disabled={processing}>
                                    {processing
                                        ? <><i className="bi bi-arrow-repeat" style={{ animation: 'spin 1s linear infinite' }}></i> Creating Account…</>
                                        : <><i className="bi bi-person-check-fill"></i> Create My Account</>
                                    }
                                </button>

                                <div className="rp-divider">or</div>

                                <div className="rp-login-cta">
                                    Already have an account?
                                    <Link href={route('login')}>Sign in here</Link>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <style>{`
                @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
            `}</style>
        </>
    );
}