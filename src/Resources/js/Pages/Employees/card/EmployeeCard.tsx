import React, { useEffect, useState } from 'react';
import { Head, usePage } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import html2pdf from 'html2pdf.js';
import QRCode from 'qrcode';
import { formatDate, getImagePath, getCompanySetting } from '@/utils/helpers';
import { useBrand } from '@/contexts/brand-context';

interface Employee {
    id: number;
    employee_id: string;
    date_of_joining: string;
    user?: { name: string; email: string; avatar: string };
    branch?: { branch_name: string };
    department?: { department_name: string };
    designation?: { designation_name: string };
}

export default function EmployeeCard() {
    const { t } = useTranslation();
    const { employee } = usePage<{ employee: Employee }>().props;
    const { settings, getPrimaryColor } = useBrand();
    const [isDownloading, setIsDownloading] = useState(false);
    const [qr, setQr] = useState('');

    const primary = getPrimaryColor() || '#1e293b';
    const logoSrc = settings.themeMode === 'dark'
        ? (settings.logo_light || settings.logo_dark)
        : (settings.logo_dark || settings.logo_light);

    // Encode the employee's identity into a scannable QR (id + name).
    useEffect(() => {
        QRCode.toDataURL(`EMP:${employee.employee_id}|${employee.user?.name || ''}`, {
            margin: 0,
            width: 160,
        }).then(setQr).catch(() => setQr(''));
    }, [employee]);

    useEffect(() => {
        const params = new URLSearchParams(window.location.search);
        if (params.get('download') === 'pdf' && qr) {
            downloadPDF();
        }
    }, [qr]);

    const downloadPDF = async () => {
        const el = document.querySelector('.card-capture');
        if (!el) return;
        const autoTriggered = new URLSearchParams(window.location.search).get('download') === 'pdf';
        setIsDownloading(true);
        try {
            await html2pdf().set({
                margin: 0,
                filename: `id-card-${employee.user?.name || employee.employee_id}.pdf`,
                image: { type: 'jpeg' as const, quality: 0.98 },
                html2canvas: { scale: 3, useCORS: true, backgroundColor: null },
                // CR80 ID-card size (mm), portrait - one card, cut to size.
                jsPDF: { unit: 'mm' as const, format: [54, 86], orientation: 'portrait' as const },
            }).from(el as HTMLElement).save();
            if (autoTriggered) setTimeout(() => window.close(), 800);
        } catch (e) {
            console.error('PDF generation failed:', e);
        }
        setIsDownloading(false);
    };

    return (
        <div style={{ minHeight: '100vh', background: '#f1f5f9', display: 'flex', flexDirection: 'column', alignItems: 'center', padding: '2rem', gap: '1.5rem' }}>
            <Head title={t('Employee ID Card')} />

            {isDownloading && (
                <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.5)', display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 50 }}>
                    <div style={{ background: '#fff', padding: '1.5rem', borderRadius: 8 }}>{t('Generating PDF...')}</div>
                </div>
            )}

            {/* Card (capture target) - inline hex styles keep html2canvas happy */}
            <div className="card-capture" style={{
                width: 324, height: 516, background: '#ffffff', borderRadius: 14,
                overflow: 'hidden', boxShadow: '0 10px 30px rgba(0,0,0,0.15)', fontFamily: 'Arial, sans-serif',
                border: '1px solid #e2e8f0', display: 'flex', flexDirection: 'column',
            }}>
                {/* Header band */}
                <div style={{ background: primary, color: '#fff', padding: '16px 16px 20px', textAlign: 'center' }}>
                    {logoSrc
                        ? <img src={getImagePath(logoSrc)} alt="logo" crossOrigin="anonymous" style={{ height: 34, margin: '0 auto 6px', objectFit: 'contain' }} />
                        : <div style={{ fontSize: 18, fontWeight: 700 }}>{getCompanySetting('company_name') || 'COMPANY'}</div>}
                    <div style={{ fontSize: 12, letterSpacing: 2, opacity: 0.9, textTransform: 'uppercase' }}>{t('Employee ID Card')}</div>
                </div>

                {/* Photo */}
                <div style={{ display: 'flex', justifyContent: 'center', marginTop: -34 }}>
                    <img
                        src={employee.user?.avatar ? getImagePath(employee.user.avatar) : '/default-avatar.png'}
                        alt={employee.user?.name || 'Employee'}
                        crossOrigin="anonymous"
                        onError={(e) => { (e.currentTarget as HTMLImageElement).src = '/default-avatar.png'; }}
                        style={{ width: 96, height: 96, borderRadius: '50%', objectFit: 'cover', border: '4px solid #fff', background: '#e2e8f0' }}
                    />
                </div>

                {/* Identity */}
                <div style={{ textAlign: 'center', padding: '10px 16px 0' }}>
                    <div style={{ fontSize: 19, fontWeight: 700, color: '#0f172a' }}>{employee.user?.name}</div>
                    <div style={{ fontSize: 13, color: primary, fontWeight: 600, marginTop: 2 }}>{employee.designation?.designation_name || '-'}</div>
                </div>

                {/* Details */}
                <div style={{ padding: '12px 20px', fontSize: 12, color: '#334155', flex: 1 }}>
                    {[
                        [t('Employee ID'), employee.employee_id],
                        [t('Department'), employee.department?.department_name || '-'],
                        [t('Branch'), employee.branch?.branch_name || '-'],
                        [t('Joined'), employee.date_of_joining ? formatDate(employee.date_of_joining) : '-'],
                    ].map(([label, value]) => (
                        <div key={label} style={{ display: 'flex', justifyContent: 'space-between', padding: '4px 0', borderBottom: '1px solid #f1f5f9' }}>
                            <span style={{ color: '#94a3b8' }}>{label}</span>
                            <span style={{ fontWeight: 600, textAlign: 'right', maxWidth: 170, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{value}</span>
                        </div>
                    ))}
                </div>

                {/* QR footer */}
                <div style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '10px 20px 16px', borderTop: `2px solid ${primary}` }}>
                    {qr && <img src={qr} alt="qr" style={{ width: 56, height: 56 }} />}
                    <div style={{ fontSize: 9, color: '#94a3b8', lineHeight: 1.4 }}>
                        {getCompanySetting('company_name') || ''}<br />
                        {t('Scan to verify')}
                    </div>
                </div>
            </div>

            {/* Actions (excluded from capture) */}
            <div style={{ display: 'flex', gap: 12 }}>
                <button onClick={downloadPDF} style={{ background: primary, color: '#fff', border: 'none', borderRadius: 8, padding: '10px 20px', fontWeight: 600, cursor: 'pointer' }}>
                    {t('Download PDF')}
                </button>
                <button onClick={() => window.print()} style={{ background: '#fff', color: '#0f172a', border: '1px solid #cbd5e1', borderRadius: 8, padding: '10px 20px', fontWeight: 600, cursor: 'pointer' }}>
                    {t('Print')}
                </button>
            </div>
        </div>
    );
}
