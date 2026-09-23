import React, { useEffect, useState, useCallback } from 'react';
import {
  IonPage, IonContent, IonRefresher, IonRefresherContent,
  IonSpinner, IonIcon
} from '@ionic/react';
import {
  walletOutline, arrowDownCircleOutline,
  receiptOutline, calendarOutline, chevronBackOutline,
  businessOutline, constructOutline, alertCircleOutline, cashOutline
} from 'ionicons/icons';
import { useHistory } from 'react-router-dom';
import axios from 'axios';
import { endpoints } from '../config/api';
import { useTheme } from '../context/ThemeContext';

interface FundEntry {
  id: number;
  type: string;
  amount: number;
  balance_after: number;
  description: string;
  date: string;
  created_at: string;
  plate_number: string | null;
}

interface FundData {
  balance: number;
  total_deposited: number;
  total_withdrawn: number;
  ledger: FundEntry[];
}

const typeConfig = (type: string) => {
  switch (type) {
    case 'deposit':
      return { label: 'Deposit', color: '#22c55e', bg: 'rgba(34,197,94,0.12)', icon: arrowDownCircleOutline, sign: '+' };
    case 'withdrawal':
      return { label: 'Cash Withdrawal', color: '#ef4444', bg: 'rgba(239,68,68,0.12)', icon: cashOutline, sign: '−' };
    case 'maintenance_share':
      return { label: 'Maintenance Deduction', color: '#f59e0b', bg: 'rgba(245,158,11,0.12)', icon: constructOutline, sign: '−' };
    case 'damage_deduction':
      return { label: 'Damage Deduction', color: '#ef4444', bg: 'rgba(239,68,68,0.12)', icon: alertCircleOutline, sign: '−' };
    case 'company_liability':
      return { label: 'Company Liability', color: '#8b5cf6', bg: 'rgba(139,92,246,0.12)', icon: businessOutline, sign: '−' };
    default:
      return { label: type, color: '#64748b', bg: 'rgba(100,116,139,0.12)', icon: receiptOutline, sign: '' };
  }
};

const fmt = (n: number) =>
  '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

const Funds: React.FC = () => {
  const history = useHistory();
  const { t, isDark } = useTheme();

  const [data, setData] = useState<FundData | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchFunds = useCallback(async () => {
    try {
      setError(null);
      const res = await axios.get(endpoints.driverFunds);
      if (res.data.success) {
        setData(res.data);
      } else {
        setError('Could not load fund data.');
      }
    } catch {
      setError('Failed to load fund data. Please check your connection.');
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => { fetchFunds(); }, [fetchFunds]);

  const handleRefresh = async (e: CustomEvent) => {
    await fetchFunds();
    (e.detail as any).complete();
  };

  const cardBg = isDark ? 'rgba(30,41,59,0.95)' : '#ffffff';
  const subtleText = isDark ? '#94a3b8' : '#64748b';

  return (
    <IonPage>
      <IonContent style={{ '--background': isDark ? '#0f172a' : '#f1f5f9' }}>
        <IonRefresher slot="fixed" onIonRefresh={handleRefresh}>
          <IonRefresherContent />
        </IonRefresher>

        {/* Header */}
        <div style={{
          background: 'linear-gradient(135deg, #059669 0%, #10b981 100%)',
          padding: '56px 20px 32px',
          position: 'relative',
          overflow: 'hidden'
        }}>
          {/* Decorative circles */}
          <div style={{ position: 'absolute', top: -40, right: -40, width: 160, height: 160, borderRadius: '50%', background: 'rgba(255,255,255,0.08)' }} />
          <div style={{ position: 'absolute', bottom: -20, left: -20, width: 100, height: 100, borderRadius: '50%', background: 'rgba(255,255,255,0.06)' }} />

          {/* Back button */}
          <button
            onClick={() => history.goBack()}
            style={{ background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: '50%', width: 36, height: 36, display: 'flex', alignItems: 'center', justifyContent: 'center', cursor: 'pointer', marginBottom: 16 }}
          >
            <IonIcon icon={chevronBackOutline} style={{ fontSize: 20, color: '#fff' }} />
          </button>

          <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 8 }}>
            <IonIcon icon={walletOutline} style={{ fontSize: 28, color: '#fff' }} />
            <span style={{ fontSize: 20, fontWeight: 700, color: '#fff' }}>My Driver Fund</span>
          </div>
          <p style={{ color: 'rgba(255,255,255,0.8)', fontSize: 13, margin: 0 }}>
            Your savings fund managed by EuroTaxi
          </p>

          {/* Balance Card */}
          {data && (
            <div style={{
              background: 'rgba(255,255,255,0.15)',
              borderRadius: 20,
              padding: '20px 24px',
              marginTop: 20,
              backdropFilter: 'blur(10px)',
              border: '1px solid rgba(255,255,255,0.2)'
            }}>
              <p style={{ color: 'rgba(255,255,255,0.75)', fontSize: 12, margin: '0 0 4px', letterSpacing: 1, textTransform: 'uppercase' }}>Available Balance</p>
              <p style={{ color: '#fff', fontSize: 36, fontWeight: 800, margin: '0 0 16px', letterSpacing: -0.5 }}>
                {fmt(data.balance)}
              </p>
              <div style={{ display: 'flex', gap: 20 }}>
                <div>
                  <p style={{ color: 'rgba(255,255,255,0.65)', fontSize: 11, margin: '0 0 2px', textTransform: 'uppercase', letterSpacing: 0.5 }}>Total Deposited</p>
                  <p style={{ color: '#a7f3d0', fontSize: 15, fontWeight: 700, margin: 0 }}>{fmt(data.total_deposited)}</p>
                </div>
                <div style={{ width: 1, background: 'rgba(255,255,255,0.2)' }} />
                <div>
                  <p style={{ color: 'rgba(255,255,255,0.65)', fontSize: 11, margin: '0 0 2px', textTransform: 'uppercase', letterSpacing: 0.5 }}>Total Deducted</p>
                  <p style={{ color: '#fca5a5', fontSize: 15, fontWeight: 700, margin: 0 }}>{fmt(data.total_withdrawn)}</p>
                </div>
              </div>
            </div>
          )}
        </div>

        {/* Content */}
        <div style={{ padding: '20px 16px 120px' }}>

          {loading && (
            <div style={{ display: 'flex', justifyContent: 'center', padding: 40 }}>
              <IonSpinner name="crescent" style={{ color: '#10b981' }} />
            </div>
          )}

          {error && (
            <div style={{ background: 'rgba(239,68,68,0.1)', border: '1px solid rgba(239,68,68,0.3)', borderRadius: 12, padding: '16px', textAlign: 'center', color: '#ef4444', fontSize: 14 }}>
              {error}
            </div>
          )}

          {data && !loading && (
            <>
              <p style={{ fontWeight: 700, fontSize: 15, color: t.textPrimary, margin: '0 0 12px' }}>
                Transaction History
                <span style={{ fontSize: 12, fontWeight: 400, color: subtleText, marginLeft: 8 }}>
                  ({data.ledger.length} records)
                </span>
              </p>

              {data.ledger.length === 0 ? (
                <div style={{ textAlign: 'center', padding: '48px 0', color: subtleText }}>
                  <IonIcon icon={walletOutline} style={{ fontSize: 48, marginBottom: 12, display: 'block', margin: '0 auto 12px' }} />
                  <p style={{ fontSize: 14 }}>No fund transactions yet.</p>
                </div>
              ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
                  {data.ledger.map((entry) => {
                    const cfg = typeConfig(entry.type);
                    return (
                      <div key={entry.id} style={{
                        background: cardBg,
                        borderRadius: 16,
                        padding: '14px 16px',
                        boxShadow: isDark ? '0 2px 8px rgba(0,0,0,0.3)' : '0 2px 8px rgba(0,0,0,0.06)',
                        border: t.border,
                        display: 'flex',
                        alignItems: 'center',
                        gap: 14
                      }}>
                        {/* Icon */}
                        <div style={{
                          width: 44, height: 44, borderRadius: 12,
                          background: cfg.bg,
                          display: 'flex', alignItems: 'center', justifyContent: 'center',
                          flexShrink: 0
                        }}>
                          <IonIcon icon={cfg.icon} style={{ fontSize: 22, color: cfg.color }} />
                        </div>

                        {/* Details */}
                        <div style={{ flex: 1, minWidth: 0 }}>
                          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                            <p style={{ margin: 0, fontWeight: 700, fontSize: 14, color: t.textPrimary }}>{cfg.label}</p>
                            <p style={{ margin: 0, fontWeight: 800, fontSize: 15, color: cfg.color, flexShrink: 0, marginLeft: 8 }}>
                              {cfg.sign}{fmt(entry.amount)}
                            </p>
                          </div>
                          <p style={{ margin: '3px 0 0', fontSize: 12, color: subtleText, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                            {entry.description || '—'}
                          </p>
                          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginTop: 6 }}>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 4 }}>
                              <IonIcon icon={calendarOutline} style={{ fontSize: 11, color: subtleText }} />
                              <span style={{ fontSize: 11, color: subtleText }}>
                                {new Date(entry.date).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' })}
                              </span>
                            </div>
                            <span style={{ fontSize: 11, color: subtleText }}>
                              Balance: {fmt(entry.balance_after)}
                            </span>
                          </div>
                        </div>
                      </div>
                    );
                  })}
                </div>
              )}
            </>
          )}
        </div>
      </IonContent>
    </IonPage>
  );
};

export default Funds;
