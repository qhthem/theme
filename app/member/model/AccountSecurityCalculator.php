<?php
// +----------------------------------------------------------------------
// | QHPHP [ 代码创造未来，思维改变世界。 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 https://www.astrocms.cn/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: ZHAOSONG <1716892803@qq.com>
// +----------------------------------------------------------------------
namespace app\member\model;

class AccountSecurityCalculator 
{
    private $passwordStrengthWeight = 0.3;
    private $twoFactorAuthWeight = 0.15;
    private $loginHistoryWeight = 0.1;
    private $accountActivityWeight = 0.15;
    private $personalInfoSecurityWeight = 0.2;

    /**
     * 计算账户安全指数
     *
     * @param string $passwordStrength 密码强度
     * @param bool $twoFactorAuth 是否启用两步验证
     * @param array $loginHistory 登录历史
     * @param array $accountActivity 账户活动
     * @param array $personalInfoSecurity 个人信息安全性
     * @return int 安全指数百分比
     */
    public function calculateSecurityIndex($passwordStrength, $twoFactorAuth, $loginHistory, $accountActivity, $personalInfoSecurity) {
        $passwordStrengthScore = $this->calculatePasswordStrengthScore($passwordStrength);
        $twoFactorAuthScore = $this->calculateTwoFactorAuthScore($twoFactorAuth);
        $loginHistoryScore = $this->calculateLoginHistoryScore($loginHistory);
        $accountActivityScore = $this->calculateAccountActivityScore($accountActivity);
        $personalInfoSecurityScore = $this->calculatePersonalInfoSecurityScore($personalInfoSecurity);

        $totalScore = $passwordStrengthScore * $this->passwordStrengthWeight +
                      $twoFactorAuthScore * $this->twoFactorAuthWeight +
                      $loginHistoryScore * $this->loginHistoryWeight +
                      $accountActivityScore * $this->accountActivityWeight +
                      $personalInfoSecurityScore * $this->personalInfoSecurityWeight;

        $percentage = $totalScore * 100; // 将安全系数乘以100以得到百分比值
        return round($percentage); // 使用round()函数将百分比值取整
    }
    
    /**
     * 计算密码强度
     *
     * @param string $password 密码
     * @return int 密码强度 (0-100)
     */
    private function calculatePasswordStrength($password) {
        $length = strlen($password);
        $strength = 0;
    
        // 密码长度
        if ($length >= 8) {
            $strength += 25;
        } else {
            $strength += $length * 5;
        }
    
        // 大写字母
        if (preg_match('/[A-Z]/', $password)) {
            $strength += 15;
        }
    
        // 小写字母
        if (preg_match('/[a-z]/', $password)) {
            $strength += 15;
        }
    
        // 数字
        if (preg_match('/\d/', $password)) {
            $strength += 15;
        }
    
        // 特殊字符
        if (preg_match('/[^a-zA-Z\d]/', $password)) {
            $strength += 15;
        }
    
        // 减少重复字符的影响
        $uniqueChars = count(array_unique(str_split($password)));
        if ($uniqueChars < $length) {
            $strength -= ($length - $uniqueChars) * 5;
        }
    
        // 限制在0-100之间
        if ($strength < 0) {
            $strength = 0;
        } elseif ($strength > 100) {
            $strength = 100;
        }
    
        return $strength;
    }

    /**
     * 根据密码强度计算得分
     *
     * @param string $passwordStrength 密码强度
     * @return float 得分
     */
    private function calculatePasswordStrengthScore($passwordStrength) {
        $passwordStrength = $this->calculatePasswordStrength($passwordStrength);
        // 根据密码强度计算得分，这里只是一个示例，你可以根据实际情况进行调整
        if ($passwordStrength >= 80) {
            return 1;
        } elseif ($passwordStrength >= 60) {
            return 0.75;
        } elseif ($passwordStrength >= 40) {
            return 0.5;
        } else {
            return 0.25;
        }
    }

    /**
     * 根据两步验证情况计算得分
     *
     * @param bool $twoFactorAuth 是否启用两步验证
     * @return int 得分
     */
    private function calculateTwoFactorAuthScore($twoFactorAuth) {
        // 根据两步验证情况计算得分
        return $twoFactorAuth ? 1 : 0;
    }

    /**
     * 根据登录历史情况计算得分
     *
     * @param array $loginHistory 登录历史
     * @return float 得分
     */
    private function calculateLoginHistoryScore($loginHistory) {
        // 根据登录历史情况计算得分，这里只是一个示例，你可以根据实际情况进行调整
        $score = 0;
        if ($loginHistory['successCount'] > 0) {
            $score += 0.5;
        }
        if ($loginHistory['failedCount'] == 0) {
            $score += 0.5;
        }
        return $score;
    }

    /**
     * 根据账户活动情况计算得分
     *
     * @param array $accountActivity 账户活动
     * @return float 得分
     */
    private function calculateAccountActivityScore($accountActivity) {
        // 根据账户活动情况计算得分，这里只是一个示例，你可以根据实际情况进行调整
        $score = 0;
        if ($accountActivity['lastActivity'] > time() - 30 * 24 * 3600) {
            $score += 0.5;
        }
        if ($accountActivity['activityCount'] > 100) {
            $score += 0.5;
        }
        return $score;
    }

    /**
     * 根据个人信息安全性情况计算得分
     *
     * @param array $personalInfoSecurity 个人信息安全性
     * @return float 得分
     */
    private function calculatePersonalInfoSecurityScore($personalInfoSecurity) {
        // 根据个人信息安全性情况计算得分，这里只是一个示例，你可以根据实际情况进行调整
        $score = 0;
        if ($personalInfoSecurity['emailVerified']) {
            $score += 0.5;
        }
        if ($personalInfoSecurity['phoneVerified']) {
            $score += 0.5;
        }
        return $score;
    }
    
    /**
     * 根据安全指数百分比获取风险等级
     *
     * @param int $securityIndexPercentage 安全指数百分比
     * @return string 风险等级
     */
    public function getRiskLevel($securityIndexPercentage) {
        if ($securityIndexPercentage >= 90) {
            return '低风险';
        } elseif ($securityIndexPercentage >= 70) {
            return '中低风险';
        } elseif ($securityIndexPercentage >= 50) {
            return '中等风险';
        } elseif ($securityIndexPercentage >= 30) {
            return '中高风险';
        } else {
            return '高风险';
        }
    }
}