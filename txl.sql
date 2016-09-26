/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : txl

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-09-26 16:16:09
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `tl_activity`
-- ----------------------------
DROP TABLE IF EXISTS `tl_activity`;
CREATE TABLE `tl_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(20) DEFAULT '' COMMENT '活动标题描述',
  `url` varchar(255) DEFAULT '' COMMENT '活动链接',
  `start_time` int(10) unsigned DEFAULT '0' COMMENT '活动开始时间',
  `end_time` int(10) unsigned DEFAULT '0' COMMENT '活动结束时间',
  `is_delete` tinyint(1) unsigned DEFAULT '0' COMMENT '是否被删除',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='活动信息表';

-- ----------------------------
-- Records of tl_activity
-- ----------------------------
INSERT INTO `tl_activity` VALUES ('1', '123123123', '/Home/ActivityQuestion/showPublishQuestion', '1474169710', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('2', '1234', '/Home/ActivityQuestion/showPublishQuestion', '1474169999', '1474169999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('3', '12313', '/Home/ActivityQuestion/showPublishQuestion', '1474169999', '1474169999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('4', '啊实打实大师', '/Home/ActivityQuestion/showPublishQuestion', '1474169999', '1474169999', '1', '1474169710', '1474194167');
INSERT INTO `tl_activity` VALUES ('5', '12312', '/Home/ActivityQuestion/showPublishQuestion', '1474169999', '1474169999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('6', '312312', '/Home/ActivityQuestion/showPublishQuestion', '1474169999', '1474169999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('7', '从自行车走心辞职信', '/Home/ActivityQuestion/showPublishQuestion', '1474169999', '1474169999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('8', 'asdas', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474169999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('9', '的萨达', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('10', 'czxcz', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('11', 'asdasdzxc', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('12', 'asdasd', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('13', '啊啊啊飒飒的', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474269999', '1', '1474169710', '1474194162');
INSERT INTO `tl_activity` VALUES ('14', 'asdzc', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474269999', '1', '1474169710', '1474194159');
INSERT INTO `tl_activity` VALUES ('15', 'asdfasdasd', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('16', 'fdgdfgdfgdf', '/Home/ActivityQuestion/showPublishQuestion', '1474279999', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('17', 'fgfg', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('18', 'sgfdg', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('19', 'sdfsdfsdfDAS', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('20', 'asdasdasd', '/Home/ActivityQuestion/showPublishQuestion', '1474279999', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('21', '恶趣味全文请', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('22', 'fsdfsdf', '/Home/ActivityQuestion/showPublishQuestion', '1474269999', '1474269999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('23', '额外认为任务额外认为任务', '/Home/ActivityQuestion/showPublishQuestion', '1474279999', '1474289999', '0', '1474169710', '1474169710');
INSERT INTO `tl_activity` VALUES ('24', '测试活动', '13123123', '1474646400', '1474646400', '0', '1474193071', '1474194019');

-- ----------------------------
-- Table structure for `tl_activity_question_bank`
-- ----------------------------
DROP TABLE IF EXISTS `tl_activity_question_bank`;
CREATE TABLE `tl_activity_question_bank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `question_tab` varchar(50) DEFAULT '' COMMENT '题目标签',
  `question_content` varchar(255) DEFAULT '' COMMENT '题目内容',
  `question_image` varchar(255) DEFAULT '' COMMENT '题目配图地址',
  `option_info` text COMMENT '选项信息',
  `state` tinyint(1) unsigned DEFAULT '0' COMMENT '状态',
  `is_next` tinyint(1) unsigned DEFAULT '0' COMMENT '是否次日发布',
  `is_publish` tinyint(1) unsigned DEFAULT '0' COMMENT '是否正在发布中',
  `last_publish_time` int(10) unsigned DEFAULT '0' COMMENT '最后发布时间',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='活动表-每日问答-题库表';

-- ----------------------------
-- Records of tl_activity_question_bank
-- ----------------------------
INSERT INTO `tl_activity_question_bank` VALUES ('1', 'common', '测试题目', '', '{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}', '1', '0', '0', '1466129408', '1463991979', '1474849781');
INSERT INTO `tl_activity_question_bank` VALUES ('2', 'common', '123123', '', '{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}', '1', '0', '0', '1474420757', '1463992600', '1474849781');
INSERT INTO `tl_activity_question_bank` VALUES ('3', 'common', '22222fff', 'Uploads/question_images/2016-06-30/5774b53199d18.jpg', '{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}', '1', '0', '0', '1467265808', '1463992615', '1474849781');
INSERT INTO `tl_activity_question_bank` VALUES ('4', 'common', '3434234aaa', 'Uploads/question_images/2016-05-25/57456d7f96e25.jpg', '{\"option\":{\"1\":\"222222\",\"2\":\"33333\",\"3\":\"44ssss\",\"4\":\"adasdcc\"},\"is_right\":\"3\"}', '1', '0', '0', '1474511298', '1463992699', '1474849781');
INSERT INTO `tl_activity_question_bank` VALUES ('5', 'common', '23123', 'Uploads/question_images/2016-05-26/5746544c9c345.jpg', '{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}', '1', '0', '0', '1474169751', '1464052401', '1474849781');
INSERT INTO `tl_activity_question_bank` VALUES ('6', 'common', '56666', 'Uploads/question_images/2016-05-24/5743ae6f3d56d.jpg', '{\"option\":{\"1\":\"ewr\\u4e8c\",\"2\":\"\\u786e\\u5b9a\\u53bb\\u5b89\\u6170\\u6211\\u53bb\",\"3\":\"\\u554a\\u5b9e\\u6253\\u5b9e\\u5927\\u5e08\",\"4\":\"\\u81ea\\u884c\\u8f66\\u7684\\u81ea\\u884c\\u8f66333\"},\"is_right\":\"3\"}', '1', '0', '0', '1464505240', '1464053359', '1474849781');
INSERT INTO `tl_activity_question_bank` VALUES ('7', 'common', '我哩个擦擦擦擦擦23333333333哈哈哈哈哈哈哈哈哈我谁谁谁水水水水水擦23333333333哈哈哈哈哈哈哈哈哈我谁谁谁水水水水水水水水啊啊啊啊啊啊啊啊啊啊啊啊啊啊，是什么？', 'Uploads/question_images/2016-05-25/574554b28c132.jpg', '{\"option\":{\"1\":\"112\",\"2\":\"333\",\"3\":\"444\",\"4\":\"555\"},\"is_right\":\"3\"}', '1', '0', '0', '1474248276', '1464161458', '1474849781');
INSERT INTO `tl_activity_question_bank` VALUES ('8', 'common', '测试2', 'Uploads/question_images/2016-05-25/574554c0d0253.jpg', '{\"option\":{\"1\":\"\\u963f\\u65af\\u987f\\u6492\\u65e6\",\"2\":\"\\u7231\\u4e0a\\u5927\\u58f0\\u5730\",\"3\":\"\\u6492\\u5927\\u58f0\\u5730\",\"4\":\"\\u6492\\u6253\\u7b97\"},\"is_right\":\"1\"}', '1', '0', '0', '1473393349', '1464161472', '1474849781');
INSERT INTO `tl_activity_question_bank` VALUES ('9', 'common', '测试3', '', '{\"option\":{\"1\":\"111\",\"2\":\"222\",\"3\":\"333\",\"4\":\"444\"},\"is_right\":\"1\"}', '1', '0', '1', '1474849781', '1464161483', '1474849781');
INSERT INTO `tl_activity_question_bank` VALUES ('10', 'common', '测试sssss啊啊啊', 'Uploads/question_images/2016-05-25/5745756877b27.jpg', '{\"option\":{\"1\":\"5555\",\"2\":\"6666\",\"3\":\"777777777777\",\"4\":\"8888\"},\"is_right\":\"1\"}', '1', '0', '0', '1470108756', '1464161500', '1474849781');
INSERT INTO `tl_activity_question_bank` VALUES ('11', 'common', '7777', 'Uploads/question_images/2016-05-25/574575071e670.jpg', '{\"option\":{\"1\":\"7\",\"2\":\"6\",\"3\":\"7\",\"4\":\"8\"},\"is_right\":\"2\"}', '1', '0', '0', '1465351970', '1464169735', '1474849781');
INSERT INTO `tl_activity_question_bank` VALUES ('12', 'common', '同一日一日一日一', '', '{\"option\":{\"1\":\"123\",\"2\":\"21313\",\"3\":\"12312\",\"4\":\"313123\"},\"is_right\":\"2\"}', '1', '0', '0', '1474350328', '1474251342', '1474849781');

-- ----------------------------
-- Table structure for `tl_activity_question_history_statistics`
-- ----------------------------
DROP TABLE IF EXISTS `tl_activity_question_history_statistics`;
CREATE TABLE `tl_activity_question_history_statistics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `question_id` int(10) unsigned DEFAULT '0' COMMENT '历史题目id',
  `question_info` text COMMENT '历史题目信息',
  `answer_statistics` varchar(255) DEFAULT '' COMMENT '历史用户回答统计',
  `user_num` int(10) unsigned DEFAULT '0' COMMENT '参与总人数',
  `publish_time` int(10) unsigned DEFAULT '0' COMMENT '问题发布时间',
  `statistics_time` int(10) unsigned DEFAULT '0' COMMENT '统计时间',
  `record_time` int(11) DEFAULT '0' COMMENT '记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='活动表-每日问答-历史回答统计表';

-- ----------------------------
-- Records of tl_activity_question_history_statistics
-- ----------------------------
INSERT INTO `tl_activity_question_history_statistics` VALUES ('17', '5', '{\"question_tab\":\"common\",\"question_content\":\"23123\",\"question_image\":\"Uploads\\/question_images\\/2016-05-26\\/5746544c9c345.jpg\",\"option_info_result\":{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}}', '[]', '0', '1464105600', '1464590895', '1464105600');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('18', '6', '{\"question_tab\":\"common\",\"question_content\":\"56666\",\"question_image\":\"Uploads\\/question_images\\/2016-05-24\\/5743ae6f3d56d.jpg\",\"option_info_result\":{\"option\":{\"1\":\"ewr\\u4e8c\",\"2\":\"\\u786e\\u5b9a\\u53bb\\u5b89\\u6170\\u6211\\u53bb\",\"3\":\"\\u554a\\u5b9e\\u6253\\u5b9e\\u5927\\u5e08\",\"4\":\"\\u81ea\\u884c\\u8f66\\u7684\\u81ea\\u884c\\u8f66333\"},\"is_right\":\"3\"}}', '{\"2\":{\"count\":2},\"4\":{\"count\":1},\"3\":{\"count\":1}}', '4', '1464505240', '1464591674', '1464451200');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('19', '7', '{\"question_tab\":\"common\",\"question_content\":\"\\u6d4b\\u8bd51\",\"question_image\":\"Uploads\\/question_images\\/2016-05-25\\/574554b28c132.jpg\",\"option_info_result\":{\"option\":{\"1\":\"112\",\"2\":\"333\",\"3\":\"444\",\"4\":\"555\"},\"is_right\":\"3\"}}', '{\"3\":{\"count\":1}}', '1', '1464591674', '1464852784', '1464537600');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('20', '4', '{\"question_tab\":\"common\",\"question_content\":\"3434234aaa\",\"question_image\":\"Uploads\\/question_images\\/2016-05-25\\/57456d7f96e25.jpg\",\"option_info_result\":{\"option\":{\"1\":\"222222\",\"2\":\"33333\",\"3\":\"44ssss\",\"4\":\"adasdcc\"},\"is_right\":\"3\"}}', '{\"3\":{\"count\":1}}', '1', '1464852784', '1465262443', '1464796800');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('21', '9', '{\"question_tab\":\"common\",\"question_content\":\"\\u6d4b\\u8bd53\",\"question_image\":\"\",\"option_info_result\":{\"option\":{\"1\":\"111\",\"2\":\"222\",\"3\":\"333\",\"4\":\"444\"},\"is_right\":\"1\"}}', '[]', '0', '1465262444', '1465351970', '1465228800');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('22', '11', '{\"question_tab\":\"common\",\"question_content\":\"7777\",\"question_image\":\"Uploads\\/question_images\\/2016-05-25\\/574575071e670.jpg\",\"option_info_result\":{\"option\":{\"1\":\"7\",\"2\":\"6\",\"3\":\"7\",\"4\":\"8\"},\"is_right\":\"2\"}}', '[]', '0', '1465351970', '1465693843', '1465315200');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('23', '3', '{\"question_tab\":\"common\",\"question_content\":\"22222fff\",\"question_image\":\"\",\"option_info_result\":{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}}', '{\"4\":{\"count\":1}}', '1', '1465693843', '1466129408', '1465660800');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('24', '1', '{\"question_tab\":\"common\",\"question_content\":\"\\u6d4b\\u8bd5\\u9898\\u76ee\",\"question_image\":\"\",\"option_info_result\":{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}}', '{\"3\":{\"count\":1}}', '1', '1466129408', '1467196011', '1466092800');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('25', '2', '{\"question_tab\":\"common\",\"question_content\":\"123123\",\"question_image\":\"\",\"option_info_result\":{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}}', '{\"2\":{\"count\":1},\"3\":{\"count\":1}}', '2', '1467196011', '1467265808', '1467129600');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('26', '3', '{\"question_tab\":\"common\",\"question_content\":\"22222fff\",\"question_image\":\"Uploads\\/question_images\\/2016-06-30\\/5774b53199d18.jpg\",\"option_info_result\":{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}}', '{\"4\":{\"count\":1}}', '1', '1467265808', '1467368288', '1467216000');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('27', '2', '{\"question_tab\":\"common\",\"question_content\":\"123123\",\"question_image\":\"\",\"option_info_result\":{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}}', '[]', '0', '1467368288', '1467686973', '1467302400');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('28', '8', '{\"question_tab\":\"common\",\"question_content\":\"\\u6d4b\\u8bd52\",\"question_image\":\"Uploads\\/question_images\\/2016-05-25\\/574554c0d0253.jpg\",\"option_info_result\":{\"option\":{\"1\":\"\\u963f\\u65af\\u987f\\u6492\\u65e6\",\"2\":\"\\u7231\\u4e0a\\u5927\\u58f0\\u5730\",\"3\":\"\\u6492\\u5927\\u58f0\\u5730\",\"4\":\"\\u6492\\u6253\\u7b97\"},\"is_right\":\"1\"}}', '{\"3\":{\"count\":1}}', '1', '1467686973', '1468391540', '1467648000');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('29', '7', '{\"question_tab\":\"common\",\"question_content\":\"\\u6d4b\\u8bd51\",\"question_image\":\"Uploads\\/question_images\\/2016-05-25\\/574554b28c132.jpg\",\"option_info_result\":{\"option\":{\"1\":\"112\",\"2\":\"333\",\"3\":\"444\",\"4\":\"555\"},\"is_right\":\"3\"}}', '{\"3\":{\"count\":1}}', '1', '1468391540', '1468491684', '1468339200');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('30', '9', '{\"question_tab\":\"common\",\"question_content\":\"\\u6d4b\\u8bd53\",\"question_image\":\"\",\"option_info_result\":{\"option\":{\"1\":\"111\",\"2\":\"222\",\"3\":\"333\",\"4\":\"444\"},\"is_right\":\"1\"}}', '[]', '0', '1468491684', '1470043238', '1468425600');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('31', '5', '{\"question_tab\":\"common\",\"question_content\":\"23123\",\"question_image\":\"Uploads\\/question_images\\/2016-05-26\\/5746544c9c345.jpg\",\"option_info_result\":{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}}', '[]', '0', '1470043238', '1470108756', '1469980800');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('32', '10', '{\"question_tab\":\"common\",\"question_content\":\"\\u6d4b\\u8bd5sssss\\u554a\\u554a\\u554a\",\"question_image\":\"Uploads\\/question_images\\/2016-05-25\\/5745756877b27.jpg\",\"option_info_result\":{\"option\":{\"1\":\"5555\",\"2\":\"6666\",\"3\":\"777777777777\",\"4\":\"8888\"},\"is_right\":\"1\"}}', '{\"3\":{\"count\":1}}', '1', '1470108756', '1470358934', '1470067200');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('33', '7', '{\"question_tab\":\"common\",\"question_content\":\"\\u6d4b\\u8bd51\",\"question_image\":\"Uploads\\/question_images\\/2016-05-25\\/574554b28c132.jpg\",\"option_info_result\":{\"option\":{\"1\":\"112\",\"2\":\"333\",\"3\":\"444\",\"4\":\"555\"},\"is_right\":\"3\"}}', '[]', '0', '1470358934', '1471680418', '1470326400');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('34', '9', '{\"question_tab\":\"common\",\"question_content\":\"\\u6d4b\\u8bd53\",\"question_image\":\"\",\"option_info_result\":{\"option\":{\"1\":\"111\",\"2\":\"222\",\"3\":\"333\",\"4\":\"444\"},\"is_right\":\"1\"}}', '[]', '0', '1471680418', '1473393349', '1471622400');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('35', '8', '{\"question_tab\":\"common\",\"question_content\":\"\\u6d4b\\u8bd52\",\"question_image\":\"Uploads\\/question_images\\/2016-05-25\\/574554c0d0253.jpg\",\"option_info_result\":{\"option\":{\"1\":\"\\u963f\\u65af\\u987f\\u6492\\u65e6\",\"2\":\"\\u7231\\u4e0a\\u5927\\u58f0\\u5730\",\"3\":\"\\u6492\\u5927\\u58f0\\u5730\",\"4\":\"\\u6492\\u6253\\u7b97\"},\"is_right\":\"1\"}}', '[]', '0', '1473393349', '1474169751', '1473350400');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('36', '5', '{\"question_tab\":\"common\",\"question_content\":\"23123\",\"question_image\":\"Uploads\\/question_images\\/2016-05-26\\/5746544c9c345.jpg\",\"option_info_result\":{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}}', '[]', '0', '1474169751', '1474248276', '1474128000');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('37', '7', '{\"question_tab\":\"common\",\"question_content\":\"\\u6211\\u54e9\\u4e2a\\u64e6\\u64e6\\u64e6\\u64e6\\u64e623333333333\\u54c8\\u54c8\\u54c8\\u54c8\\u54c8\\u54c8\\u54c8\\u54c8\\u54c8\\u6211\\u8c01\\u8c01\\u8c01\\u6c34\\u6c34\\u6c34\\u6c34\\u6c34\\u64e623333333333\\u54c8\\u54c8\\u54c8\\u54c8\\u54c8\\u54c8\\u54c8\\u54c8\\u54c8\\u6211\\u8c01\\u8c01\\u8c01\\u6c34\\u6c34\\u6c34\\u6c34\\u6c34\\u6c34\\u6c34\\u6c34\\u554a\\u554a\\u554a\\u554a\\u554a\\u554a\\u554a\\u554a\\u554a\\u554a\\u554a\\u554a\\u554a\\u554a\\uff0c\\u662f\\u4ec0\\u4e48\\uff1f\",\"question_image\":\"Uploads\\/question_images\\/2016-05-25\\/574554b28c132.jpg\",\"option_info_result\":{\"option\":{\"1\":\"112\",\"2\":\"333\",\"3\":\"444\",\"4\":\"555\"},\"is_right\":\"3\"}}', '{\"3\":{\"count\":1},\"2\":{\"count\":1}}', '2', '1474248276', '1474350328', '1474214400');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('38', '12', '{\"question_tab\":\"common\",\"question_content\":\"\\u540c\\u4e00\\u65e5\\u4e00\\u65e5\\u4e00\\u65e5\\u4e00\",\"question_image\":\"\",\"option_info_result\":{\"option\":{\"1\":\"123\",\"2\":\"21313\",\"3\":\"12312\",\"4\":\"313123\"},\"is_right\":\"2\"}}', '[]', '0', '1474350328', '1474420757', '1474300800');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('39', '2', '{\"question_tab\":\"common\",\"question_content\":\"123123\",\"question_image\":\"\",\"option_info_result\":{\"option\":{\"1\":\"22\",\"2\":\"33\",\"3\":\"44\",\"4\":\"adasd\"},\"is_right\":\"2\"}}', '{\"3\":{\"count\":1}}', '1', '1474420757', '1474511298', '1474387200');
INSERT INTO `tl_activity_question_history_statistics` VALUES ('40', '4', '{\"question_tab\":\"common\",\"question_content\":\"3434234aaa\",\"question_image\":\"Uploads\\/question_images\\/2016-05-25\\/57456d7f96e25.jpg\",\"option_info_result\":{\"option\":{\"1\":\"222222\",\"2\":\"33333\",\"3\":\"44ssss\",\"4\":\"adasdcc\"},\"is_right\":\"3\"}}', '[]', '0', '1474511298', '1474849781', '1474473600');

-- ----------------------------
-- Table structure for `tl_activity_question_user_answer`
-- ----------------------------
DROP TABLE IF EXISTS `tl_activity_question_user_answer`;
CREATE TABLE `tl_activity_question_user_answer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `question_id` int(10) unsigned DEFAULT '0' COMMENT '题目id',
  `answer` char(5) DEFAULT '' COMMENT '用户回答',
  `answer_time` int(10) unsigned DEFAULT '0' COMMENT '回答时间',
  `is_right` tinyint(1) unsigned DEFAULT '0' COMMENT '回答结果是否正确',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='活动表-每日问答-用户回答记录表';

-- ----------------------------
-- Records of tl_activity_question_user_answer
-- ----------------------------
INSERT INTO `tl_activity_question_user_answer` VALUES ('17', '1', '6', '2', '1464505240', '0', '1464591466');
INSERT INTO `tl_activity_question_user_answer` VALUES ('18', '13', '6', '4', '1464505240', '0', '1464591493');
INSERT INTO `tl_activity_question_user_answer` VALUES ('19', '12', '6', '2', '1464505240', '0', '1464591531');
INSERT INTO `tl_activity_question_user_answer` VALUES ('20', '9', '6', '3', '1464505240', '1', '1464591615');
INSERT INTO `tl_activity_question_user_answer` VALUES ('21', '1', '7', '3', '1464592223', '1', '1464592223');
INSERT INTO `tl_activity_question_user_answer` VALUES ('22', '1', '4', '3', '1464852787', '1', '1464852787');
INSERT INTO `tl_activity_question_user_answer` VALUES ('23', '1', '3', '4', '1465693846', '0', '1465693846');
INSERT INTO `tl_activity_question_user_answer` VALUES ('24', '1', '1', '3', '1466129411', '0', '1466129411');
INSERT INTO `tl_activity_question_user_answer` VALUES ('25', '14', '2', '2', '1467196090', '1', '1467196090');
INSERT INTO `tl_activity_question_user_answer` VALUES ('26', '1', '2', '3', '1467196165', '0', '1467196165');
INSERT INTO `tl_activity_question_user_answer` VALUES ('27', '1', '3', '4', '1467265811', '0', '1467265811');
INSERT INTO `tl_activity_question_user_answer` VALUES ('28', '1', '8', '3', '1467686976', '0', '1467686976');
INSERT INTO `tl_activity_question_user_answer` VALUES ('29', '1', '7', '3', '1468391542', '1', '1468391542');
INSERT INTO `tl_activity_question_user_answer` VALUES ('30', '1', '10', '3', '1470108761', '0', '1470108761');
INSERT INTO `tl_activity_question_user_answer` VALUES ('33', '1', '7', '3', '1474268901', '1', '1474268901');
INSERT INTO `tl_activity_question_user_answer` VALUES ('34', '2', '7', '2', '1474268910', '0', '1474268910');
INSERT INTO `tl_activity_question_user_answer` VALUES ('35', '1', '2', '3', '1474420761', '0', '1474420761');

-- ----------------------------
-- Table structure for `tl_attr`
-- ----------------------------
DROP TABLE IF EXISTS `tl_attr`;
CREATE TABLE `tl_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增属性id',
  `parent_id` int(10) unsigned DEFAULT '0' COMMENT '父级id',
  `attr_name` varchar(255) DEFAULT '' COMMENT '属性名',
  `state` tinyint(1) unsigned DEFAULT '1' COMMENT '状态 0 =>删除 1 => 正常',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='属性信息表';

-- ----------------------------
-- Records of tl_attr
-- ----------------------------
INSERT INTO `tl_attr` VALUES ('1', '0', '测试属性111', '0', '1459404320', '1459411535');
INSERT INTO `tl_attr` VALUES ('2', '0', '测试属性22', '1', '1459404333', '1459416641');
INSERT INTO `tl_attr` VALUES ('3', '0', '测试属性31111', '1', '1459404349', '1462954718');
INSERT INTO `tl_attr` VALUES ('4', '0', '测试2的6', '1', '1459408593', '1467686907');
INSERT INTO `tl_attr` VALUES ('5', '2', '测试2的自家', '1', '1459408728', '1459408728');
INSERT INTO `tl_attr` VALUES ('6', '5', '哈哈哈', '1', '1459409438', '1459409438');
INSERT INTO `tl_attr` VALUES ('7', '2', '哈哈哈1', '1', '1459409447', '1459416647');
INSERT INTO `tl_attr` VALUES ('8', '4', '1116', '1', '1459409453', '1462954742');
INSERT INTO `tl_attr` VALUES ('9', '3', '2222', '1', '1459409458', '1459409458');
INSERT INTO `tl_attr` VALUES ('10', '3', '333啊1', '1', '1459409461', '1459416725');
INSERT INTO `tl_attr` VALUES ('11', '10', '啊', '1', '1459409465', '1459409465');
INSERT INTO `tl_attr` VALUES ('12', '11', '啊', '1', '1459409469', '1459409469');
INSERT INTO `tl_attr` VALUES ('13', '11', 'z', '1', '1459409473', '1459409473');
INSERT INTO `tl_attr` VALUES ('14', '12', 'z', '1', '1459409481', '1459409481');
INSERT INTO `tl_attr` VALUES ('15', '10', 'z', '1', '1459409488', '1459409488');
INSERT INTO `tl_attr` VALUES ('16', '8', 'z66', '1', '1459409521', '1467686924');
INSERT INTO `tl_attr` VALUES ('17', '4', 'z11', '0', '1459409526', '1459409526');
INSERT INTO `tl_attr` VALUES ('18', '0', 'z11', '0', '1459409530', '1459409530');
INSERT INTO `tl_attr` VALUES ('19', '14', '问问', '0', '1459416318', '1459416318');
INSERT INTO `tl_attr` VALUES ('20', '14', '问问1', '0', '1459416324', '1459416324');
INSERT INTO `tl_attr` VALUES ('21', '12', '3123', '1', '1462955220', '1462955220');
INSERT INTO `tl_attr` VALUES ('22', '12', '31232', '1', '1462955235', '1462955235');
INSERT INTO `tl_attr` VALUES ('23', '4', 'qqqe', '1', '1466392847', '1466392847');
INSERT INTO `tl_attr` VALUES ('24', '0', 'adsadqq', '1', '1467196318', '1467196326');
INSERT INTO `tl_attr` VALUES ('25', '24', 'qweqwe', '1', '1467196336', '1467196336');
INSERT INTO `tl_attr` VALUES ('26', '25', 'qweqwedasd', '1', '1467196340', '1467196340');
INSERT INTO `tl_attr` VALUES ('27', '24', 'qweqwe222', '1', '1467196351', '1467196351');
INSERT INTO `tl_attr` VALUES ('28', '0', '666', '1', '1467686883', '1467686883');
INSERT INTO `tl_attr` VALUES ('29', '8', '66', '1', '1467686916', '1467686916');

-- ----------------------------
-- Table structure for `tl_cart`
-- ----------------------------
DROP TABLE IF EXISTS `tl_cart`;
CREATE TABLE `tl_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品id',
  `pay_type` tinyint(1) unsigned DEFAULT '1' COMMENT '选择支付种类  1=>money 2=>积分',
  `goods_num` int(10) unsigned DEFAULT '1' COMMENT '商品数量',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `last_time` int(10) unsigned DEFAULT '0' COMMENT '最后购买时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='用户清单表（购物车、收藏）';

-- ----------------------------
-- Records of tl_cart
-- ----------------------------
INSERT INTO `tl_cart` VALUES ('11', '13', '31', '1', '1', '1470210622', '0');
INSERT INTO `tl_cart` VALUES ('12', '13', '27', '1', '1', '1470210625', '0');
INSERT INTO `tl_cart` VALUES ('24', '3', '41', '1', '2', '1472695523', '0');
INSERT INTO `tl_cart` VALUES ('25', '8', '31', '1', '4', '1472696903', '0');
INSERT INTO `tl_cart` VALUES ('27', '2', '25', '1', '2', '1474341261', '0');
INSERT INTO `tl_cart` VALUES ('29', '1', '35', '1', '2', '1474364385', '0');

-- ----------------------------
-- Table structure for `tl_fund`
-- ----------------------------
DROP TABLE IF EXISTS `tl_fund`;
CREATE TABLE `tl_fund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `fund` decimal(10,2) DEFAULT '0.00' COMMENT '账号余额',
  `profit` decimal(10,2) DEFAULT '0.00' COMMENT '总利润',
  `income` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '总收入',
  `expenses` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '总支出',
  `withdraw` decimal(10,2) DEFAULT '0.00' COMMENT '总提现',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='资金记录表';

-- ----------------------------
-- Records of tl_fund
-- ----------------------------
INSERT INTO `tl_fund` VALUES ('1', '-767.40', '-546.40', '1819.60', '2684.00', '221.00', '1474337855', '1474610856');

-- ----------------------------
-- Table structure for `tl_fund_log`
-- ----------------------------
DROP TABLE IF EXISTS `tl_fund_log`;
CREATE TABLE `tl_fund_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `fund` decimal(10,2) DEFAULT '0.00' COMMENT '流动资金',
  `result_fund` decimal(10,2) DEFAULT '0.00' COMMENT '流动结果',
  `remark` varchar(255) DEFAULT '' COMMENT '流动备注',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '资金流动类型',
  `is_statistics` tinyint(1) unsigned DEFAULT '0' COMMENT '是否已被统计',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='资金流水日志';

-- ----------------------------
-- Records of tl_fund_log
-- ----------------------------
INSERT INTO `tl_fund_log` VALUES ('2', '123.00', '123.00', '1312313asdeasdasd实打实大师大师打的', '1', '1', '1470882658');
INSERT INTO `tl_fund_log` VALUES ('3', '-1234.00', '-1111.00', '请问企鹅企鹅企鹅', '1', '1', '1470883558');
INSERT INTO `tl_fund_log` VALUES ('4', '1231.00', '120.00', '2222', '1', '1', '1471056458');
INSERT INTO `tl_fund_log` VALUES ('5', '-10.00', '110.00', '1231313123', '1', '1', '1473475645');
INSERT INTO `tl_fund_log` VALUES ('6', '-22.00', '88.00', '啊嗯大神大神大神大神的', '1', '1', '1473476125');
INSERT INTO `tl_fund_log` VALUES ('7', '-100.00', '-12.00', '阿萨斯大神大神大神大神的', '1', '1', '1474339775');
INSERT INTO `tl_fund_log` VALUES ('8', '123.00', '111.00', '啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊阿事实上事实上是事实上事实上事实上事实上事实上', '1', '1', '1474339795');
INSERT INTO `tl_fund_log` VALUES ('9', '47.60', '158.60', '订单 D0920147434098042898 完成，获取收益：47.60', '1', '1', '1474341018');
INSERT INTO `tl_fund_log` VALUES ('10', '47.60', '206.20', '订单 D0920147434098042898 完成，获取收益：47.60', '1', '1', '1474341045');
INSERT INTO `tl_fund_log` VALUES ('11', '88.00', '294.20', '订单 D0920147434126886613 完成，获取收益：88.00', '1', '1', '1474341288');
INSERT INTO `tl_fund_log` VALUES ('12', '23.80', '318.00', '订单 D0920147434119782306 完成，获取收益：23.80', '1', '1', '1474341300');
INSERT INTO `tl_fund_log` VALUES ('13', '-110.00', '208.00', '资金提现:拿走110', '2', '1', '1474358179');
INSERT INTO `tl_fund_log` VALUES ('14', '-111.00', '97.00', '资金提现:222222', '2', '1', '1474358689');
INSERT INTO `tl_fund_log` VALUES ('15', '-1000.00', '-903.00', '爆炸', '1', '1', '1474359923');
INSERT INTO `tl_fund_log` VALUES ('16', '47.60', '-855.40', '订单 D0920147436447257765 完成，获取收益：47.60', '1', '1', '1474421587');
INSERT INTO `tl_fund_log` VALUES ('17', '88.00', '-767.40', '订单 D0923147460148442508 完成，获取收益：88.00', '1', '0', '1474610856');

-- ----------------------------
-- Table structure for `tl_goods`
-- ----------------------------
DROP TABLE IF EXISTS `tl_goods`;
CREATE TABLE `tl_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `belong_id` int(10) unsigned DEFAULT '0' COMMENT '属归id',
  `name` varchar(50) DEFAULT '' COMMENT '商品名称',
  `ext_name` varchar(50) DEFAULT '' COMMENT '商品扩展名',
  `attr_id` int(10) unsigned DEFAULT '0' COMMENT '属性id',
  `price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '商品单价',
  `point` int(11) DEFAULT '0' COMMENT '商品所需积分',
  `can_price` tinyint(1) DEFAULT '1' COMMENT '允许price结算',
  `can_point` tinyint(1) DEFAULT '0' COMMENT '允许point结算',
  `describe` text COMMENT '商品描述',
  `state` tinyint(1) unsigned DEFAULT '1' COMMENT '商品状态 0 锁定 1 正常 2 删除',
  `is_shop` tinyint(1) unsigned DEFAULT '0' COMMENT '是否正常在售  0 下架  1 上架',
  `shelve_time` int(10) unsigned DEFAULT '0' COMMENT '最后一次上架时间',
  `goods_image` varchar(255) DEFAULT '' COMMENT '商品图片路径',
  `weight` int(10) unsigned DEFAULT '0' COMMENT '权重',
  `is_recommend` tinyint(1) unsigned DEFAULT '0' COMMENT '是否被推荐',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='商品信息表';

-- ----------------------------
-- Records of tl_goods
-- ----------------------------
INSERT INTO `tl_goods` VALUES ('1', '1', '11', '11', '0', '222.78', '0', '1', '0', '222222', '2', '1', '1459418669', '', '0', '0', '1458546499', '1463133116');
INSERT INTO `tl_goods` VALUES ('2', '1', '11d2', '1122', '0', '222.78', '0', '1', '0', '222222', '2', '1', '1459156346', '', '0', '0', '1458546666', '1462951892');
INSERT INTO `tl_goods` VALUES ('3', '1', '测试1', '测试扩展名', '6', '2.00', '0', '1', '0', '阿萨德按时打算', '1', '1', '1470018417', 'Uploads/goods_images/2016-08-01/579eb35ceca8c.jpg', '0', '0', '1458894255', '1470018417');
INSERT INTO `tl_goods` VALUES ('4', '1', '123123', '123123', '21', '5.50', '0', '1', '0', '2323', '1', '1', '1471336973', '', '0', '0', '1458895766', '1471336973');
INSERT INTO `tl_goods` VALUES ('5', '1', '222', '333', '16', '55.00', '0', '1', '0', '66', '1', '1', '1466390578', '', '0', '0', '1459156940', '1466390578');
INSERT INTO `tl_goods` VALUES ('6', '1', '22', '22', '16', '22.00', '0', '1', '0', '11', '1', '1', '1466390582', '', '0', '0', '1459156950', '1466390582');
INSERT INTO `tl_goods` VALUES ('7', '1', '33', '44', '16', '66.00', '0', '1', '0', '666', '1', '0', '1465714267', '', '0', '0', '1459156956', '1466148745');
INSERT INTO `tl_goods` VALUES ('8', '1', '332', '44', '6', '66.00', '0', '1', '0', '666', '1', '1', '1466390572', '', '0', '0', '1459156963', '1466390572');
INSERT INTO `tl_goods` VALUES ('9', '1', '3321', '44', '7', '66.00', '0', '1', '0', '666', '1', '1', '1466390566', '', '0', '0', '1459156966', '1466390566');
INSERT INTO `tl_goods` VALUES ('10', '1', '33213', '44', '0', '66.00', '0', '1', '0', '666', '1', '1', '0', '', '0', '0', '1459156968', '1459472900');
INSERT INTO `tl_goods` VALUES ('11', '1', '33213', '441', '0', '66.00', '0', '1', '0', '666', '1', '1', '0', '', '0', '0', '1459156971', '1459472896');
INSERT INTO `tl_goods` VALUES ('12', '1', '33213', '4412', '13', '66.00', '0', '1', '0', '666', '1', '1', '1466390569', '', '0', '0', '1459156973', '1466390569');
INSERT INTO `tl_goods` VALUES ('13', '1', '33213', '112', '0', '66.00', '0', '1', '0', '666', '1', '1', '0', '', '0', '0', '1459156977', '1459472890');
INSERT INTO `tl_goods` VALUES ('14', '1', '1', '112', '0', '66.00', '0', '1', '0', '666', '1', '1', '0', '', '0', '0', '1459156980', '1459472886');
INSERT INTO `tl_goods` VALUES ('15', '1', '2', '112', '4', '66.00', '0', '1', '0', '666', '1', '0', '1459418665', '', '0', '0', '1459156982', '1466148412');
INSERT INTO `tl_goods` VALUES ('16', '1', '3', '112', '5', '66.00', '0', '1', '0', '666', '1', '1', '1466390607', '', '0', '0', '1459156986', '1466390607');
INSERT INTO `tl_goods` VALUES ('17', '1', '4', '112', '2', '66.00', '0', '1', '0', '666', '1', '1', '1466390604', '', '0', '0', '1459156989', '1466390604');
INSERT INTO `tl_goods` VALUES ('18', '1', '41', '112', '6', '66.00', '0', '1', '0', '666', '1', '1', '1466390601', '', '0', '0', '1459156993', '1466390601');
INSERT INTO `tl_goods` VALUES ('19', '1', '412', '112', '5', '66.00', '0', '1', '0', '666', '1', '1', '1466390599', '', '0', '0', '1459156996', '1466390599');
INSERT INTO `tl_goods` VALUES ('20', '1', '223', '112', '3', '66.00', '0', '1', '0', '666', '1', '1', '1466390595', '', '0', '0', '1459156999', '1466390595');
INSERT INTO `tl_goods` VALUES ('21', '1', '123', '112', '15', '66.00', '23', '1', '0', '666', '1', '1', '1467686843', '', '0', '0', '1459157003', '1467686843');
INSERT INTO `tl_goods` VALUES ('22', '1', '223', '3', '9', '4.00', '0', '1', '0', '5', '1', '1', '1466390613', '', '0', '0', '1459157020', '1466390613');
INSERT INTO `tl_goods` VALUES ('23', '1', '223111', '3', '9', '4.00', '0', '1', '0', '5', '1', '1', '1466390610', '', '0', '0', '1459157024', '1466390610');
INSERT INTO `tl_goods` VALUES ('24', '1', '测试', '哈哈哈', '15', '12.00', '0', '1', '0', '按时大大', '1', '0', '1465714210', '', '0', '0', '1459304371', '1471402964');
INSERT INTO `tl_goods` VALUES ('25', '1', '12', '22', '2', '44.00', '2', '1', '1', '555', '1', '1', '1473131275', 'Uploads/goods_images/2016-09-06/57ce32fd8ffce.jpg', '0', '1', '1459473741', '1473131275');
INSERT INTO `tl_goods` VALUES ('26', '1', '123', '333', '5', '11.00', '0', '1', '0', '222', '1', '1', '1465714206', '', '0', '0', '1459473970', '1465714206');
INSERT INTO `tl_goods` VALUES ('27', '1', '12355', '333', '7', '11.00', '0', '1', '0', '222', '1', '0', '1465714204', '', '0', '0', '1459474015', '1472009148');
INSERT INTO `tl_goods` VALUES ('28', '1', '123', '123', '0', '123.00', '0', '1', '0', '123123', '2', '0', '0', 'Uploads/goods_images/2016-04-01/56fe2d82d31e4.jpg', '0', '0', '1459495188', '1461807799');
INSERT INTO `tl_goods` VALUES ('29', '1', '6666', '12312', '0', '1231231.00', '0', '1', '0', '123123', '2', '0', '0', 'Uploads/goods_images/2016-04-01/56fe3cb065d6f.jpg', '0', '0', '1459498412', '1461807764');
INSERT INTO `tl_goods` VALUES ('30', '1', '21312312', '123123', '10', '3123.00', '0', '1', '0', '123123', '2', '1', '1465714184', 'Uploads/goods_images/2016-06-12/575d06015065e.jpg', '0', '0', '1461807383', '1471403049');
INSERT INTO `tl_goods` VALUES ('31', '1', '测试标签', '商品', '2', '12.00', '0', '1', '0', '啊飒飒大师的', '1', '1', '1465714201', 'Uploads/goods_images/2016-04-29/57230475ebea2.jpg', '0', '0', '1461912693', '1465714201');
INSERT INTO `tl_goods` VALUES ('32', '1', '7777', '777', '4', '678.00', '22', '1', '1', '768', '1', '0', '1470996834', 'Uploads/goods_images/2016-08-31/57c68782f1954.jpg', '0', '0', '1461912773', '1472628610');
INSERT INTO `tl_goods` VALUES ('33', '1', '22', '777', '5', '678.00', '0', '1', '0', '768', '1', '1', '1466390589', 'Uploads/goods_images/2016-06-08/5757e2bf6e339.jpg', '0', '0', '1461913220', '1466390589');
INSERT INTO `tl_goods` VALUES ('34', '1', '222', '777', '0', '678.00', '0', '1', '0', '768', '2', '0', '0', '', '0', '0', '1461913260', '1462951882');
INSERT INTO `tl_goods` VALUES ('35', '1', 'qeqweqweasdasdasdasd q呜呜呜', '啊实打实大师大师大师大师大师大师的', '2', '23.80', '123', '1', '1', 'wqeqwe123123123123123213大师大师大师的', '1', '1', '1472711954', 'Uploads/goods_images/2016-07-29/579ad0b077c45.jpg', '0', '0', '1462945812', '1472711954');
INSERT INTO `tl_goods` VALUES ('36', '1', '测试测试测试测试测试测试测试233', '测试测试测试测试测试测地方', '7', '111.82', '0', '1', '0', '测试测试测试测试22', '1', '1', '1474364359', 'Uploads/goods_images/2016-05-11/5732d3dac2618.jpg', '66', '1', '1462948471', '1474364359');
INSERT INTO `tl_goods` VALUES ('39', '1', '2233认为', '111234', '0', '6.00', '0', '1', '1', '213', '2', '0', '0', 'Uploads/goods_images/2016-06-12/575cf94e7c340.jpg', '0', '0', '1465710926', '1467196244');
INSERT INTO `tl_goods` VALUES ('40', '1', 'xinsd', 'sadad', '6', '2.00', '0', '1', '0', '3123123', '2', '0', '1467196242', 'Uploads/goods_images/2016-07-05/577b11cadad69.jpg', '0', '0', '1467196205', '1467686831');
INSERT INTO `tl_goods` VALUES ('41', '1', 'aedasd', 'qeqw', '11', '23.00', '5', '1', '1', '111', '1', '1', '1467686826', 'Uploads/goods_images/2016-07-05/577b12637e9b6.jpg', '0', '0', '1467683427', '1467686826');
INSERT INTO `tl_goods` VALUES ('42', '1', '12sss', 'asdasd', '0', '111.00', '22', '1', '0', '12312aseqwe', '1', '1', '1474364343', 'Uploads/goods_images/2016-07-26/57972826417ba.jpg', '6', '1', '1469524006', '1474364343');

-- ----------------------------
-- Table structure for `tl_goods_stock`
-- ----------------------------
DROP TABLE IF EXISTS `tl_goods_stock`;
CREATE TABLE `tl_goods_stock` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品id',
  `stock` int(11) DEFAULT '0' COMMENT '库存数量',
  `stock_unit` varchar(10) DEFAULT '' COMMENT '库存单位',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='商品库存信息表';

-- ----------------------------
-- Records of tl_goods_stock
-- ----------------------------
INSERT INTO `tl_goods_stock` VALUES ('2', '36', '5', '', '1465373509', '1465378087');
INSERT INTO `tl_goods_stock` VALUES ('3', '30', '66', '个', '1465378097', '1465379477');
INSERT INTO `tl_goods_stock` VALUES ('4', '35', '22', '只', '1465379347', '1474601465');
INSERT INTO `tl_goods_stock` VALUES ('5', '33', '0', '只', '1465379571', '1472261110');
INSERT INTO `tl_goods_stock` VALUES ('6', '32', '86', '把', '1465693925', '1472631517');
INSERT INTO `tl_goods_stock` VALUES ('7', '41', '12', '吨', '1470906342', '1474424572');
INSERT INTO `tl_goods_stock` VALUES ('8', '27', '8', '', '1471401671', '1472003075');
INSERT INTO `tl_goods_stock` VALUES ('9', '31', '0', '', '1472088224', '1472711893');
INSERT INTO `tl_goods_stock` VALUES ('10', '25', '2', '', '1473131281', '1474601484');
INSERT INTO `tl_goods_stock` VALUES ('11', '42', '22', '', '1474364345', '1474364349');

-- ----------------------------
-- Table structure for `tl_goods_stock_log`
-- ----------------------------
DROP TABLE IF EXISTS `tl_goods_stock_log`;
CREATE TABLE `tl_goods_stock_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品id',
  `change_stock` int(11) DEFAULT '0' COMMENT '改变库存',
  `result_stock` int(11) DEFAULT '0' COMMENT '结果库存',
  `real_stock` int(11) DEFAULT '0' COMMENT '实际库存',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='商品库存记录表';

-- ----------------------------
-- Records of tl_goods_stock_log
-- ----------------------------
INSERT INTO `tl_goods_stock_log` VALUES ('1', '41', '12', '12', '12', '1474424016');
INSERT INTO `tl_goods_stock_log` VALUES ('2', '41', '-1', '11', '11', '1474424111');
INSERT INTO `tl_goods_stock_log` VALUES ('3', '35', '-1', '23', '23', '1474424111');
INSERT INTO `tl_goods_stock_log` VALUES ('4', '25', '-1', '3', '3', '1474424111');
INSERT INTO `tl_goods_stock_log` VALUES ('5', '41', '1', '12', '12', '1474424572');
INSERT INTO `tl_goods_stock_log` VALUES ('6', '35', '1', '24', '24', '1474424573');
INSERT INTO `tl_goods_stock_log` VALUES ('7', '25', '1', '4', '4', '1474424573');
INSERT INTO `tl_goods_stock_log` VALUES ('8', '35', '-2', '22', '22', '1474601465');
INSERT INTO `tl_goods_stock_log` VALUES ('9', '25', '-2', '2', '2', '1474601484');

-- ----------------------------
-- Table structure for `tl_goods_tag_relate`
-- ----------------------------
DROP TABLE IF EXISTS `tl_goods_tag_relate`;
CREATE TABLE `tl_goods_tag_relate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品id',
  `tag_id` int(10) unsigned DEFAULT '0' COMMENT '标签id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='商品标签关联表';

-- ----------------------------
-- Records of tl_goods_tag_relate
-- ----------------------------
INSERT INTO `tl_goods_tag_relate` VALUES ('13', '33', '1');
INSERT INTO `tl_goods_tag_relate` VALUES ('14', '33', '3');
INSERT INTO `tl_goods_tag_relate` VALUES ('15', '33', '8');
INSERT INTO `tl_goods_tag_relate` VALUES ('16', '33', '9');
INSERT INTO `tl_goods_tag_relate` VALUES ('18', '33', '23');
INSERT INTO `tl_goods_tag_relate` VALUES ('19', '35', '3');
INSERT INTO `tl_goods_tag_relate` VALUES ('20', '35', '13');
INSERT INTO `tl_goods_tag_relate` VALUES ('21', '35', '17');
INSERT INTO `tl_goods_tag_relate` VALUES ('22', '36', '1');
INSERT INTO `tl_goods_tag_relate` VALUES ('23', '36', '11');
INSERT INTO `tl_goods_tag_relate` VALUES ('24', '36', '15');
INSERT INTO `tl_goods_tag_relate` VALUES ('25', '36', '17');
INSERT INTO `tl_goods_tag_relate` VALUES ('26', '36', '23');
INSERT INTO `tl_goods_tag_relate` VALUES ('27', '36', '3');
INSERT INTO `tl_goods_tag_relate` VALUES ('28', '18', '3');
INSERT INTO `tl_goods_tag_relate` VALUES ('29', '18', '12');
INSERT INTO `tl_goods_tag_relate` VALUES ('30', '18', '13');
INSERT INTO `tl_goods_tag_relate` VALUES ('31', '18', '23');
INSERT INTO `tl_goods_tag_relate` VALUES ('32', '40', '12');
INSERT INTO `tl_goods_tag_relate` VALUES ('33', '40', '17');
INSERT INTO `tl_goods_tag_relate` VALUES ('34', '40', '7');
INSERT INTO `tl_goods_tag_relate` VALUES ('35', '40', '15');
INSERT INTO `tl_goods_tag_relate` VALUES ('36', '41', '12');
INSERT INTO `tl_goods_tag_relate` VALUES ('37', '41', '15');
INSERT INTO `tl_goods_tag_relate` VALUES ('38', '41', '17');

-- ----------------------------
-- Table structure for `tl_notice`
-- ----------------------------
DROP TABLE IF EXISTS `tl_notice`;
CREATE TABLE `tl_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(20) DEFAULT '' COMMENT '公告标题',
  `message` text COMMENT '公告内容',
  `count` int(10) unsigned DEFAULT '0' COMMENT '浏览量',
  `is_top` tinyint(1) unsigned DEFAULT '0' COMMENT '是否置顶',
  `is_delete` tinyint(1) unsigned DEFAULT '0' COMMENT '是否被删除',
  `author` int(10) unsigned DEFAULT '0' COMMENT '作者',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='公告表';

-- ----------------------------
-- Records of tl_notice
-- ----------------------------
INSERT INTO `tl_notice` VALUES ('1', '文章', '<p>asd<span style=\"color: rgb(255, 0, 0);\">fa<span style=\"font-size: 24px;\"><em>sdasdas</em></span>da</span><span style=\"border: 1px solid rgb(0, 0, 0);\"><span style=\"color: rgb(255, 0, 0);\">sdas</span>d</span></p>', '0', '0', '0', '1', '1473821772', '1473832474');
INSERT INTO `tl_notice` VALUES ('2', '按时大大是大大', '<p><img src=\"/Uploads/ueditor/image/20160914/1473821067146308.jpg\" title=\"1473821067146308.jpg\" alt=\"empty.jpg\" width=\"95\" height=\"150\"/>asdfa<span style=\"font-size: 24px;\"><em>sda<span style=\"color: rgb(255, 255, 0);\">sdas</span></em></span><span style=\"color: rgb(255, 255, 0);\">dasd</span>asd</p>', '0', '0', '0', '1', '1473832086', '1473832086');
INSERT INTO `tl_notice` VALUES ('3', '测试文章1', '<p>自行车自行车自行车自行车自行车在<br/></p>', '0', '0', '0', '1', '1473832798', '1473832798');
INSERT INTO `tl_notice` VALUES ('4', '测试文章2', '<p>擦擦擦擦擦<span style=\"font-size: 20px;\">擦擦擦擦擦</span>擦擦</p><p style=\"text-align: center;\"><img src=\"/Uploads/ueditor/image/20160918/1474163185365641.jpg\" title=\"1474163185365641.jpg\"/></p><p style=\"text-align: center;\"><img src=\"/Uploads/ueditor/image/20160918/1474163185104270.gif\" title=\"1474163185104270.gif\"/></p><p><span style=\"font-size: 20px;\">擦擦擦</span><span style=\"color: rgb(0, 176, 80);\">擦擦<span style=\"font-size: 20px;\">擦擦擦</span>擦擦<span style=\"font-size: 20px;\">擦擦擦</span>擦擦</span></p>', '1', '1', '0', '1', '1473834136', '1474163213');
INSERT INTO `tl_notice` VALUES ('5', '测试文章5', '<p>阿萨飒飒大师大师的<br/></p>', '0', '0', '0', '1', '1473834344', '1473834344');
INSERT INTO `tl_notice` VALUES ('6', '12312312', '<p>312312312313<br/></p>', '0', '0', '0', '1', '1473834352', '1473834352');
INSERT INTO `tl_notice` VALUES ('7', 'asdasdasd', '<p>大师大师大师的<br/></p>', '0', '0', '0', '1', '1473834362', '1473834362');
INSERT INTO `tl_notice` VALUES ('8', 'asdasdasd123123', '<p>123123123123<br/></p>', '0', '0', '0', '1', '1473834369', '1473834369');
INSERT INTO `tl_notice` VALUES ('9', 'asdasdasd123123', '<p>又有意义有意义有意义有意义有意义有意义有意义有意义有意义有意义<br/></p>', '1', '1', '0', '1', '1473834387', '1473836951');
INSERT INTO `tl_notice` VALUES ('10', '3213131', '<p>2312321311大大大大<br/></p>', '1', '0', '0', '1', '1473834411', '1473834411');
INSERT INTO `tl_notice` VALUES ('11', '啊实打实大师大师的', '<p>撒大声大声道<br/></p>', '0', '0', '0', '1', '1473834614', '1473834614');
INSERT INTO `tl_notice` VALUES ('12', '3123123', '<p>123天天嘎嘎嘎嘎嘎嘎<br/></p>', '1', '0', '0', '1', '1473834630', '1473834630');
INSERT INTO `tl_notice` VALUES ('13', '123123', '<p>大神大神大神大神的<br/></p>', '0', '0', '1', '1', '1473834686', '1473836947');

-- ----------------------------
-- Table structure for `tl_order`
-- ----------------------------
DROP TABLE IF EXISTS `tl_order`;
CREATE TABLE `tl_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_code` varchar(20) DEFAULT '' COMMENT '订单序列号',
  `goods_code` text COMMENT '商品串码',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `order_price` decimal(10,2) DEFAULT '0.00' COMMENT '订单金额',
  `pay_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '实付金额',
  `point` int(10) unsigned DEFAULT '0' COMMENT '支付积分',
  `goods_num` int(10) unsigned DEFAULT '0' COMMENT '涉及商品种类总数',
  `goods_all_num` int(10) unsigned DEFAULT '0' COMMENT '商品总数',
  `send_week` tinyint(1) unsigned DEFAULT '0' COMMENT '配送周',
  `send_time` char(15) DEFAULT '' COMMENT '配送时间段',
  `send_address` varchar(255) DEFAULT '' COMMENT '配送详细地址',
  `state` tinyint(1) unsigned DEFAULT '1' COMMENT '订单状态 1=>待确认,2=>待发货,3=>配送中,4=>待结算,5=>已完成,6=>已关闭,7=>有异议,8=>已退单',
  `is_confirm` tinyint(1) unsigned DEFAULT '0' COMMENT '是否已被用户确认',
  `confirm_mobile` char(20) DEFAULT '' COMMENT '确认手机号',
  `confirm_time` int(10) unsigned DEFAULT '0' COMMENT '订单确认时间',
  `is_pay` tinyint(1) DEFAULT '0' COMMENT '已付款',
  `confirm_pay_time` int(10) unsigned DEFAULT '0' COMMENT '确认付款时间',
  `remark` varchar(255) DEFAULT '' COMMENT '备注信息',
  `operation_remark` varchar(255) DEFAULT '' COMMENT '订单操作备注',
  `is_delete` tinyint(1) unsigned DEFAULT '0' COMMENT '是否被删除',
  `delete_time` int(10) unsigned DEFAULT '0' COMMENT '删除时间',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '下单时间',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `success_time` int(10) unsigned DEFAULT '0' COMMENT '订单完成时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Records of tl_order
-- ----------------------------
INSERT INTO `tl_order` VALUES ('35', 'D0831147262653246719', 'd20n4t1d19n1t2', '1', '48.00', '48.00', '5', '2', '5', '7', 'night', '啊实打实大山大啊实打实的21312312', '5', '1', '18857877819', '1472635456', '1', '1472635456', '啊实打实大师大师的方法反反复复反复反复反复反复反复反复反复反复反复反复反复反复', '', '0', '0', '1472626532', '1472806430', '0');
INSERT INTO `tl_order` VALUES ('36', 'D0831147263157483751', 'd20n1t1d19n2t1', '1', '58.00', '58.00', '0', '2', '3', '6', 'lunch', '啊实打实大山大啊实打实的', '5', '1', '18857877819', '1472712060', '1', '1472806604', '13123213213', '', '0', '0', '1472631574', '1472806615', '0');
INSERT INTO `tl_order` VALUES ('37', 'D0901147269544855131', 'd23n6t1d22n1t1', '2', '95.00', '95.00', '0', '2', '7', '7', 'dinner', '我是新人', '5', '1', '18888888888', '1472697009', '1', '1472697009', '鞍山地区大师大师大师的', '', '0', '0', '1472695448', '1472806429', '0');
INSERT INTO `tl_order` VALUES ('38', 'D0901147269554919810', 'd24n2t1', '3', '46.00', '46.00', '0', '1', '2', '7', 'dinner', '和健康和健康和健康', '5', '1', '18855555000', '1472695640', '1', '1472806599', '金龟换酒过好几个家', '', '0', '0', '1472695549', '1472806615', '0');
INSERT INTO `tl_order` VALUES ('39', 'D0901147269692094100', 'd25n4t1', '8', '48.00', '48.00', '0', '1', '4', '7', 'night', 'fghfghfgh', '5', '1', '18866666666', '1472808118', '1', '1472808117', 'fghfghgfgggggggggggggggg', '', '0', '0', '1472696920', '1472808146', '0');
INSERT INTO `tl_order` VALUES ('40', 'D0901147271189328890', 'd23n15t1', '2', '180.00', '180.00', '0', '1', '15', '7', 'afternoon', '4535345', '8', '1', '18888888888', '1472712016', '1', '1472712016', '345345345', '', '0', '0', '1472711893', '1473130960', '0');
INSERT INTO `tl_order` VALUES ('41', 'D0901147271197294520', 'd26n4t1', '1', '95.20', '95.20', '0', '1', '4', '6', 'lunch', '啊实打实大山大啊实打实的', '5', '1', '18857877819', '1472712025', '1', '1472795773', '', '', '0', '0', '1472711972', '1472805253', '0');
INSERT INTO `tl_order` VALUES ('42', 'D0901147271715831398', 'd26n4t1', '1', '95.20', '95.20', '0', '1', '4', '7', 'night', '啊实打实大山大啊实打实的', '1', '0', '', '0', '0', '0', 'dasdasdasdasd', '', '1', '1472717176', '1472717158', '1472717158', '0');
INSERT INTO `tl_order` VALUES ('43', 'D0901147271725094012', 'd26n4t1', '1', '95.20', '95.20', '0', '1', '4', '6', 'lunch', '啊实打实大山大啊实打实的', '1', '0', '', '0', '0', '0', '', '', '1', '1472717264', '1472717250', '1472717250', '0');
INSERT INTO `tl_order` VALUES ('44', 'D0901147271725471888', 'd26n4t1', '1', '95.20', '95.20', '0', '1', '4', '6', 'lunch', '啊实打实大山大啊实打实的', '6', '0', '', '0', '0', '0', '', '', '0', '0', '1472717254', '1473126556', '0');
INSERT INTO `tl_order` VALUES ('45', 'D0901147271727143997', 'd26n4t1', '1', '95.20', '95.20', '0', '1', '4', '6', 'lunch', '啊实打实大山大啊实打实的', '8', '1', '18857877819', '1472795857', '1', '1472805450', '', '', '0', '0', '1472717271', '1473130939', '0');
INSERT INTO `tl_order` VALUES ('46', 'D0906147313132283861', 'd27n1t2d26n1t1', '1', '23.80', '23.80', '2', '2', '2', '6', 'lunch', '啊实打实大山大啊实打实的', '5', '1', '18857877819', '1473131504', '1', '1473131503', 'adasdasdadasdadasdadasd', '', '0', '0', '1473131322', '1473131537', '0');
INSERT INTO `tl_order` VALUES ('47', 'D0906147313134422889', 'd27n4t1', '1', '176.00', '176.00', '0', '1', '4', '6', 'afternoon', '啊实打实大山大啊实打实的sssssss', '6', '1', '18857877819', '1473131394', '0', '0', 'cfsdfsfsdfsdfsdfsdfsdfsdfsd', '1212', '0', '0', '1473131344', '1473131461', '0');
INSERT INTO `tl_order` VALUES ('48', 'D0906147313161280603', 'd27n4t1', '1', '176.00', '176.00', '0', '1', '4', '6', 'night', '啊实打实大山大啊实打实的', '8', '1', '18857877819', '1473131626', '1', '1473131626', '快快快快快快快快快快快快快快快快快快快快快快快快快快快快快快快快快快快快', '3333', '0', '0', '1473131612', '1473131647', '0');
INSERT INTO `tl_order` VALUES ('49', 'D0906147313316226575', 'd27n4t1', '1', '176.00', '176.00', '0', '1', '4', '6', 'lunch', '啊实打实大山大啊实打实的', '6', '0', '', '0', '0', '0', '', '7y7777', '0', '0', '1473133162', '1473133175', '0');
INSERT INTO `tl_order` VALUES ('50', 'D0906147313352919129', 'd27n3t1', '1', '132.00', '132.00', '0', '1', '3', '6', 'lunch', 'sadasdasd', '5', '1', '18857877819', '1473412930', '1', '1473413039', 'kljkljkl', '', '0', '0', '1473133529', '1473413060', '0');
INSERT INTO `tl_order` VALUES ('51', 'D0909147340498062421', 'd27n3t1', '1', '132.00', '132.00', '0', '1', '3', '6', 'lunch', 'sadasdasd', '5', '1', '18857877819', '1473412924', '1', '1473412924', '', '', '0', '0', '1473404980', '1473412954', '0');
INSERT INTO `tl_order` VALUES ('52', 'D0913147373908769117', 'd26n2t1', '1', '88.00', '88.00', '0', '1', '2', '6', 'lunch', 'sadasdasd', '5', '1', '18857877819', '1473739120', '1', '1473739120', '按时打算打算打', '', '0', '0', '1473739087', '1473739141', '0');
INSERT INTO `tl_order` VALUES ('53', 'D0919147426708754263', 'd26n4t1', '2', '95.20', '95.20', '0', '1', '4', '6', 'dinner', '我家厕所酷炫~~~~', '5', '1', '18888888888', '1474267187', '1', '1474267521', '给老子快点送来~~~~~', '', '0', '0', '1474267087', '1474267534', '0');
INSERT INTO `tl_order` VALUES ('54', 'D0920147434098042898', 'd26n2t1', '2', '47.60', '47.60', '0', '1', '2', '6', 'lunch', '我家厕所酷炫~~~~', '5', '1', '18888888888', '1474341002', '1', '1474341041', '', '', '0', '0', '1474340980', '1474341045', '0');
INSERT INTO `tl_order` VALUES ('55', 'D0920147434119782306', 'd26n1t1', '2', '23.80', '23.80', '0', '1', '1', '6', 'lunch', '我家厕所酷炫~~~~', '5', '1', '18888888888', '1474341218', '1', '1474341245', '', '', '0', '0', '1474341197', '1474341299', '0');
INSERT INTO `tl_order` VALUES ('56', 'D0920147434126886613', 'd27n2t1', '2', '88.00', '88.00', '0', '1', '2', '6', 'lunch', '我家厕所酷炫~~~~', '5', '1', '18888888888', '1474341279', '1', '1474341279', '', '', '0', '0', '1474341268', '1474341288', '0');
INSERT INTO `tl_order` VALUES ('57', 'D0920147436440626410', 'd29n2t1d28n1t2', '1', '47.60', '47.60', '2', '2', '3', '6', 'lunch', 'sadasdasd', '1', '0', '', '0', '0', '0', '', '', '1', '1474364423', '1474364406', '1474364406', '0');
INSERT INTO `tl_order` VALUES ('58', 'D0920147436447257765', 'd29n2t1d28n1t2', '1', '47.60', '47.60', '2', '2', '3', '7', 'dinner', 'sadasdasdxdzxczxczx', '8', '1', '18857877819', '1474366367', '1', '1474421579', 'asdasdasdasd啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊阿啊啊啊', '觉得不行', '0', '0', '1474364472', '1474422647', '0');
INSERT INTO `tl_order` VALUES ('59', 'D0921147442411122598', 'd30n1t1d29n1t1d28n1t1', '1', '90.80', '90.80', '0', '3', '3', '6', 'night', 'tyutyutyutyu', '6', '1', '18857877819', '1474424505', '0', '0', 'tyutyuytuytu', 'yongh buyao l', '0', '0', '1474424111', '1474424572', '0');
INSERT INTO `tl_order` VALUES ('60', 'D0923147460146594504', 'd29n2t1', '1', '47.60', '47.60', '0', '1', '2', '6', 'lunch', 'sadasdasd', '1', '0', '', '0', '0', '0', '', '', '0', '0', '1474601465', '1474601465', '0');
INSERT INTO `tl_order` VALUES ('61', 'D0923147460148442508', 'd27n2t1', '2', '88.00', '88.00', '0', '1', '2', '6', 'lunch', '我家厕所酷炫~~~~', '5', '1', '18888888888', '1474610803', '1', '1474610852', '', '', '0', '0', '1474601484', '1474610856', '1474610856');

-- ----------------------------
-- Table structure for `tl_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `tl_order_goods`;
CREATE TABLE `tl_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品id',
  `order_id` int(10) unsigned DEFAULT '0' COMMENT '订单id',
  `goods_num` int(10) unsigned DEFAULT '0' COMMENT '购买数量',
  `pay_type` tinyint(1) unsigned DEFAULT '1' COMMENT '支付方式',
  `price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '下单时的商品价格',
  `point` int(10) unsigned DEFAULT '0' COMMENT '下单时的商品积分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8 COMMENT='订单关联商品表';

-- ----------------------------
-- Records of tl_order_goods
-- ----------------------------
INSERT INTO `tl_order_goods` VALUES ('70', '31', '35', '4', '1', '12.00', '0');
INSERT INTO `tl_order_goods` VALUES ('71', '41', '35', '1', '2', '23.00', '5');
INSERT INTO `tl_order_goods` VALUES ('72', '31', '36', '1', '1', '12.00', '0');
INSERT INTO `tl_order_goods` VALUES ('73', '41', '36', '2', '1', '23.00', '5');
INSERT INTO `tl_order_goods` VALUES ('74', '31', '37', '6', '1', '12.00', '0');
INSERT INTO `tl_order_goods` VALUES ('75', '41', '37', '1', '1', '23.00', '5');
INSERT INTO `tl_order_goods` VALUES ('76', '41', '38', '2', '1', '23.00', '5');
INSERT INTO `tl_order_goods` VALUES ('77', '31', '39', '4', '1', '12.00', '0');
INSERT INTO `tl_order_goods` VALUES ('78', '31', '40', '15', '1', '12.00', '0');
INSERT INTO `tl_order_goods` VALUES ('79', '35', '41', '4', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('80', '35', '42', '4', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('81', '35', '43', '4', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('82', '35', '44', '4', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('83', '35', '45', '4', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('84', '25', '46', '1', '2', '44.00', '2');
INSERT INTO `tl_order_goods` VALUES ('85', '35', '46', '1', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('86', '25', '47', '4', '1', '44.00', '2');
INSERT INTO `tl_order_goods` VALUES ('87', '25', '48', '4', '1', '44.00', '2');
INSERT INTO `tl_order_goods` VALUES ('88', '25', '49', '4', '1', '44.00', '2');
INSERT INTO `tl_order_goods` VALUES ('89', '25', '50', '3', '1', '44.00', '2');
INSERT INTO `tl_order_goods` VALUES ('90', '25', '51', '3', '1', '44.00', '2');
INSERT INTO `tl_order_goods` VALUES ('91', '25', '52', '2', '1', '44.00', '2');
INSERT INTO `tl_order_goods` VALUES ('92', '35', '53', '4', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('93', '35', '54', '2', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('94', '35', '55', '1', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('95', '25', '56', '2', '1', '44.00', '2');
INSERT INTO `tl_order_goods` VALUES ('96', '35', '57', '2', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('97', '25', '57', '1', '2', '44.00', '2');
INSERT INTO `tl_order_goods` VALUES ('98', '35', '58', '2', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('99', '25', '58', '1', '2', '44.00', '2');
INSERT INTO `tl_order_goods` VALUES ('100', '41', '59', '1', '1', '23.00', '5');
INSERT INTO `tl_order_goods` VALUES ('101', '35', '59', '1', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('102', '25', '59', '1', '1', '44.00', '2');
INSERT INTO `tl_order_goods` VALUES ('103', '35', '60', '2', '1', '23.80', '123');
INSERT INTO `tl_order_goods` VALUES ('104', '25', '61', '2', '1', '44.00', '2');

-- ----------------------------
-- Table structure for `tl_order_log`
-- ----------------------------
DROP TABLE IF EXISTS `tl_order_log`;
CREATE TABLE `tl_order_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` int(10) unsigned DEFAULT '0' COMMENT '订单id',
  `relevant` varchar(255) DEFAULT '' COMMENT '相关数据',
  `log` varchar(255) DEFAULT '' COMMENT '订单日志内容',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8 COMMENT='订单日志表';

-- ----------------------------
-- Records of tl_order_log
-- ----------------------------
INSERT INTO `tl_order_log` VALUES ('1', '42', '用户id：1', '订单生成成功。订单id：42，订单序列号：D0901147271715831398', '1472717158');
INSERT INTO `tl_order_log` VALUES ('2', '42', '用户id：1', '订单id：42，被删除', '1472717176');
INSERT INTO `tl_order_log` VALUES ('3', '43', '用户id：1', '订单生成成功。订单id：43，订单序列号：D0901147271725094012', '1472717250');
INSERT INTO `tl_order_log` VALUES ('4', '44', '用户id：1', '订单生成成功。订单id：44，订单序列号：D0901147271725471888', '1472717254');
INSERT INTO `tl_order_log` VALUES ('5', '43', '用户id：1', '订单id：43，被删除', '1472717264');
INSERT INTO `tl_order_log` VALUES ('6', '45', '用户id：1', '订单生成成功。订单id：45，订单序列号：D0901147271727143997', '1472717271');
INSERT INTO `tl_order_log` VALUES ('7', '41', '', '订单已开始配送', '1472795582');
INSERT INTO `tl_order_log` VALUES ('8', '40', '', '订单已开始配送', '1472795582');
INSERT INTO `tl_order_log` VALUES ('9', '38', '', '订单已开始配送', '1472795582');
INSERT INTO `tl_order_log` VALUES ('10', '37', '', '订单已开始配送', '1472795582');
INSERT INTO `tl_order_log` VALUES ('11', '36', '', '订单已开始配送', '1472795583');
INSERT INTO `tl_order_log` VALUES ('12', '35', '', '订单已开始配送', '1472795583');
INSERT INTO `tl_order_log` VALUES ('13', '41', '', '订单被确认已付款', '1472795773');
INSERT INTO `tl_order_log` VALUES ('14', '45', '', '订单被确认通过', '1472795857');
INSERT INTO `tl_order_log` VALUES ('15', '45', '', '订单已开始配送', '1472795864');
INSERT INTO `tl_order_log` VALUES ('16', '45', '', '订单进入待结算状态', '1472805253');
INSERT INTO `tl_order_log` VALUES ('17', '41', '', '订单已完成', '1472805253');
INSERT INTO `tl_order_log` VALUES ('18', '45', '', '订单被确认已付款', '1472805450');
INSERT INTO `tl_order_log` VALUES ('19', '45', '', '订单已完成', '1472805459');
INSERT INTO `tl_order_log` VALUES ('20', '40', '', '订单已完成', '1472806429');
INSERT INTO `tl_order_log` VALUES ('21', '38', '', '订单进入待结算状态', '1472806429');
INSERT INTO `tl_order_log` VALUES ('22', '37', '', '订单已完成', '1472806429');
INSERT INTO `tl_order_log` VALUES ('23', '36', '', '订单进入待结算状态', '1472806430');
INSERT INTO `tl_order_log` VALUES ('24', '35', '', '订单已完成', '1472806430');
INSERT INTO `tl_order_log` VALUES ('25', '38', '', '订单被确认已付款', '1472806599');
INSERT INTO `tl_order_log` VALUES ('26', '36', '', '订单被确认已付款', '1472806604');
INSERT INTO `tl_order_log` VALUES ('27', '38', '', '订单已完成', '1472806615');
INSERT INTO `tl_order_log` VALUES ('28', '36', '', '订单已完成', '1472806615');
INSERT INTO `tl_order_log` VALUES ('29', '39', '', '订单被确认已付款', '1472808117');
INSERT INTO `tl_order_log` VALUES ('30', '39', '', '订单被确认通过', '1472808118');
INSERT INTO `tl_order_log` VALUES ('31', '39', '', '订单已开始配送', '1472808127');
INSERT INTO `tl_order_log` VALUES ('32', '39', '', '订单已完成', '1472808146');
INSERT INTO `tl_order_log` VALUES ('33', '44', '', '订单因为：过期了 被关闭', '1473126556');
INSERT INTO `tl_order_log` VALUES ('34', '45', '', '订单因为：312313 已退款', '1473130939');
INSERT INTO `tl_order_log` VALUES ('35', '40', '', '订单因为：13123 已退款', '1473130960');
INSERT INTO `tl_order_log` VALUES ('36', '46', '用户id：1', '订单生成成功。订单id：46，订单序列号：D0906147313132283861', '1473131322');
INSERT INTO `tl_order_log` VALUES ('37', '47', '用户id：1', '订单生成成功。订单id：47，订单序列号：D0906147313134422889', '1473131344');
INSERT INTO `tl_order_log` VALUES ('38', '47', '', '订单被确认通过', '1473131394');
INSERT INTO `tl_order_log` VALUES ('39', '47', '', '订单因为：不给了 被关闭', '1473131461');
INSERT INTO `tl_order_log` VALUES ('40', '46', '', '订单被确认已付款', '1473131504');
INSERT INTO `tl_order_log` VALUES ('41', '46', '', '订单被确认通过', '1473131504');
INSERT INTO `tl_order_log` VALUES ('42', '46', '', '订单已开始配送', '1473131527');
INSERT INTO `tl_order_log` VALUES ('43', '46', '', '订单已完成', '1473131537');
INSERT INTO `tl_order_log` VALUES ('44', '48', '用户id：1', '订单生成成功。订单id：48，订单序列号：D0906147313161280603', '1473131612');
INSERT INTO `tl_order_log` VALUES ('45', '48', '', '订单被确认已付款', '1473131626');
INSERT INTO `tl_order_log` VALUES ('46', '48', '', '订单被确认通过', '1473131626');
INSERT INTO `tl_order_log` VALUES ('47', '48', '', '订单已开始配送', '1473131637');
INSERT INTO `tl_order_log` VALUES ('48', '48', '', '订单因为：56757 已退款', '1473131647');
INSERT INTO `tl_order_log` VALUES ('49', '49', '用户id：1', '订单生成成功。订单id：49，订单序列号：D0906147313316226575', '1473133162');
INSERT INTO `tl_order_log` VALUES ('50', '49', '', '订单因为：7y7777 被关闭', '1473133175');
INSERT INTO `tl_order_log` VALUES ('51', '49', '用户id：1', '订单id：49，仅恢复库存', '1473133175');
INSERT INTO `tl_order_log` VALUES ('52', '50', '用户id：1', '订单生成成功。订单id：50，订单序列号：D0906147313352919129', '1473133529');
INSERT INTO `tl_order_log` VALUES ('53', '51', '用户id：1', '订单生成成功。订单id：51，订单序列号：D0909147340498062421', '1473404980');
INSERT INTO `tl_order_log` VALUES ('54', '51', '', '订单被确认已付款', '1473412924');
INSERT INTO `tl_order_log` VALUES ('55', '51', '', '订单被确认通过', '1473412924');
INSERT INTO `tl_order_log` VALUES ('56', '50', '', '订单被确认通过', '1473412930');
INSERT INTO `tl_order_log` VALUES ('57', '51', '', '订单已开始配送', '1473412950');
INSERT INTO `tl_order_log` VALUES ('58', '50', '', '订单已开始配送', '1473412950');
INSERT INTO `tl_order_log` VALUES ('59', '51', '', '订单已完成', '1473412954');
INSERT INTO `tl_order_log` VALUES ('60', '50', '', '订单进入待结算状态', '1473412954');
INSERT INTO `tl_order_log` VALUES ('61', '50', '', '订单被确认已付款', '1473413039');
INSERT INTO `tl_order_log` VALUES ('62', '50', '', '订单已完成', '1473413060');
INSERT INTO `tl_order_log` VALUES ('63', '52', '用户id：1', '订单生成成功。订单id：52，订单序列号：D0913147373908769117', '1473739087');
INSERT INTO `tl_order_log` VALUES ('64', '52', '', '订单被确认已付款', '1473739120');
INSERT INTO `tl_order_log` VALUES ('65', '52', '', '订单被确认通过', '1473739120');
INSERT INTO `tl_order_log` VALUES ('66', '52', '', '订单已开始配送', '1473739132');
INSERT INTO `tl_order_log` VALUES ('67', '52', '', '订单已完成', '1473739141');
INSERT INTO `tl_order_log` VALUES ('68', '53', '用户id：2', '订单生成成功。订单id：53，订单序列号：D0919147426708754263', '1474267087');
INSERT INTO `tl_order_log` VALUES ('69', '53', '', '订单被确认通过', '1474267187');
INSERT INTO `tl_order_log` VALUES ('70', '53', '', '订单已开始配送', '1474267201');
INSERT INTO `tl_order_log` VALUES ('71', '53', '', '订单进入待结算状态', '1474267223');
INSERT INTO `tl_order_log` VALUES ('72', '53', '', '订单被确认已付款', '1474267521');
INSERT INTO `tl_order_log` VALUES ('73', '53', '', '订单已完成', '1474267534');
INSERT INTO `tl_order_log` VALUES ('74', '54', '用户id：2', '订单生成成功。订单id：54，订单序列号：D0920147434098042898', '1474340980');
INSERT INTO `tl_order_log` VALUES ('75', '54', '', '订单被确认通过', '1474341002');
INSERT INTO `tl_order_log` VALUES ('76', '54', '', '订单已开始配送', '1474341012');
INSERT INTO `tl_order_log` VALUES ('77', '54', '', '订单进入待结算状态', '1474341018');
INSERT INTO `tl_order_log` VALUES ('78', '54', '', '订单被确认已付款', '1474341041');
INSERT INTO `tl_order_log` VALUES ('79', '54', '', '订单已完成', '1474341045');
INSERT INTO `tl_order_log` VALUES ('80', '55', '用户id：2', '订单生成成功。订单id：55，订单序列号：D0920147434119782306', '1474341197');
INSERT INTO `tl_order_log` VALUES ('81', '55', '', '订单被确认通过', '1474341218');
INSERT INTO `tl_order_log` VALUES ('82', '55', '', '订单已开始配送', '1474341223');
INSERT INTO `tl_order_log` VALUES ('83', '55', '', '订单进入待结算状态', '1474341228');
INSERT INTO `tl_order_log` VALUES ('84', '55', '', '订单被确认已付款', '1474341245');
INSERT INTO `tl_order_log` VALUES ('85', '56', '用户id：2', '订单生成成功。订单id：56，订单序列号：D0920147434126886613', '1474341268');
INSERT INTO `tl_order_log` VALUES ('86', '56', '', '订单被确认已付款', '1474341279');
INSERT INTO `tl_order_log` VALUES ('87', '56', '', '订单被确认通过', '1474341279');
INSERT INTO `tl_order_log` VALUES ('88', '56', '', '订单已开始配送', '1474341283');
INSERT INTO `tl_order_log` VALUES ('89', '56', '', '订单已完成', '1474341288');
INSERT INTO `tl_order_log` VALUES ('90', '55', '', '订单已完成', '1474341299');
INSERT INTO `tl_order_log` VALUES ('91', '57', '用户id：1', '订单生成成功。订单id：57，订单序列号：D0920147436440626410', '1474364406');
INSERT INTO `tl_order_log` VALUES ('92', '57', '用户id：1', '订单id：57，被删除', '1474364423');
INSERT INTO `tl_order_log` VALUES ('93', '58', '用户id：1', '订单生成成功。订单id：58，订单序列号：D0920147436447257765', '1474364472');
INSERT INTO `tl_order_log` VALUES ('94', '58', '', '订单被确认通过', '1474366367');
INSERT INTO `tl_order_log` VALUES ('95', '58', '', '订单已开始配送', '1474366389');
INSERT INTO `tl_order_log` VALUES ('96', '58', '', '订单进入待结算状态', '1474366413');
INSERT INTO `tl_order_log` VALUES ('97', '58', '', '订单被确认已付款', '1474421579');
INSERT INTO `tl_order_log` VALUES ('98', '58', '', '订单已完成', '1474421587');
INSERT INTO `tl_order_log` VALUES ('99', '58', '', '订单因为：觉得不行 已退款', '1474422638');
INSERT INTO `tl_order_log` VALUES ('100', '58', '用户id：1', '订单id：58，仅恢复库存', '1474422638');
INSERT INTO `tl_order_log` VALUES ('101', '58', '', '订单因为：觉得不行 已退款', '1474422647');
INSERT INTO `tl_order_log` VALUES ('102', '58', '用户id：1', '订单id：58，仅恢复库存', '1474422647');
INSERT INTO `tl_order_log` VALUES ('103', '59', '用户id：1', '订单生成成功。订单id：59，订单序列号：D0921147442411122598', '1474424111');
INSERT INTO `tl_order_log` VALUES ('104', '59', '', '订单被确认通过', '1474424505');
INSERT INTO `tl_order_log` VALUES ('105', '59', '', '订单因为：yongh buyao l 被关闭', '1474424572');
INSERT INTO `tl_order_log` VALUES ('106', '59', '用户id：1', '订单id：59，仅恢复库存', '1474424573');
INSERT INTO `tl_order_log` VALUES ('107', '60', '用户id：1', '订单生成成功。订单id：60，订单序列号：D0923147460146594504', '1474601465');
INSERT INTO `tl_order_log` VALUES ('108', '61', '用户id：2', '订单生成成功。订单id：61，订单序列号：D0923147460148442508', '1474601484');
INSERT INTO `tl_order_log` VALUES ('109', '61', '', '订单被确认通过', '1474610803');
INSERT INTO `tl_order_log` VALUES ('110', '61', '', '订单已开始配送', '1474610830');
INSERT INTO `tl_order_log` VALUES ('111', '61', '', '订单进入待结算状态', '1474610837');
INSERT INTO `tl_order_log` VALUES ('112', '61', '', '订单被确认已付款', '1474610852');
INSERT INTO `tl_order_log` VALUES ('113', '61', '', '订单已完成', '1474610856');

-- ----------------------------
-- Table structure for `tl_param`
-- ----------------------------
DROP TABLE IF EXISTS `tl_param`;
CREATE TABLE `tl_param` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `param_key` varchar(255) DEFAULT '' COMMENT '参数键名',
  `param_value` text COMMENT '参数值键',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='全站基础参数配置表';

-- ----------------------------
-- Records of tl_param
-- ----------------------------
INSERT INTO `tl_param` VALUES ('1', 'GoodsListShowAttr', '[{\"title\":\"第一个栏目\",\"attr_id\":\"2\"},{\"title\":\"第二个栏目\",\"attr_id\":\"3\"},{\"title\":\"第三个栏目\",\"attr_id\":\"4\"},{\"title\":\"第四个栏目\",\"attr_id\":\"10\"}]', '1469698875', '1469698875');
INSERT INTO `tl_param` VALUES ('2', 'FundStatisticsLastTime', '{\"time\":1474610594}', '1474610594', '1474610594');
INSERT INTO `tl_param` VALUES ('3', 'GoodsSaleStatisticsLastTime', '{\"time\":1474443698}', '1474443698', '1474443698');
INSERT INTO `tl_param` VALUES ('4', 'WebState', '{\"is_close\":0,\"remark\":\"\",\"update_time\":1474453382}', '1474453382', '1474453382');

-- ----------------------------
-- Table structure for `tl_search_keyword`
-- ----------------------------
DROP TABLE IF EXISTS `tl_search_keyword`;
CREATE TABLE `tl_search_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `keyword` varchar(100) DEFAULT '' COMMENT '搜索关键词',
  `count` int(10) unsigned DEFAULT '1' COMMENT '搜索次数',
  `is_hot` tinyint(1) unsigned DEFAULT '0' COMMENT '是否为热词（前台优先级很高）',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='搜索关键词记录表';

-- ----------------------------
-- Records of tl_search_keyword
-- ----------------------------
INSERT INTO `tl_search_keyword` VALUES ('1', '12', '2', '0', '1474421193', '1474421396');

-- ----------------------------
-- Table structure for `tl_statistics_attr`
-- ----------------------------
DROP TABLE IF EXISTS `tl_statistics_attr`;
CREATE TABLE `tl_statistics_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `attr_id` int(10) unsigned DEFAULT '0' COMMENT '属性id',
  `goods_num` int(10) unsigned DEFAULT '0' COMMENT '商品数量统计',
  `statistics_time` int(10) unsigned DEFAULT '0' COMMENT '统计时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='商品属性统计表';

-- ----------------------------
-- Records of tl_statistics_attr
-- ----------------------------
INSERT INTO `tl_statistics_attr` VALUES ('19', '6', '3', '1474420789');
INSERT INTO `tl_statistics_attr` VALUES ('20', '21', '1', '1474420789');
INSERT INTO `tl_statistics_attr` VALUES ('21', '16', '2', '1474420789');
INSERT INTO `tl_statistics_attr` VALUES ('22', '7', '2', '1474420790');
INSERT INTO `tl_statistics_attr` VALUES ('23', '13', '1', '1474420790');
INSERT INTO `tl_statistics_attr` VALUES ('24', '5', '4', '1474420790');
INSERT INTO `tl_statistics_attr` VALUES ('25', '2', '4', '1474420790');
INSERT INTO `tl_statistics_attr` VALUES ('26', '3', '1', '1474420790');
INSERT INTO `tl_statistics_attr` VALUES ('27', '9', '2', '1474420790');
INSERT INTO `tl_statistics_attr` VALUES ('28', '15', '1', '1474420790');
INSERT INTO `tl_statistics_attr` VALUES ('31', '11', '1', '1474420790');

-- ----------------------------
-- Table structure for `tl_statistics_fund`
-- ----------------------------
DROP TABLE IF EXISTS `tl_statistics_fund`;
CREATE TABLE `tl_statistics_fund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `profit` decimal(10,2) DEFAULT '0.00' COMMENT '利润',
  `income` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '收入',
  `expenses` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '支出',
  `withdraw` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '提现',
  `record_time` int(10) unsigned DEFAULT '0' COMMENT '记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='资金流水日统计表';

-- ----------------------------
-- Records of tl_statistics_fund
-- ----------------------------
INSERT INTO `tl_statistics_fund` VALUES ('1', '-1111.00', '123.00', '1234.00', '0.00', '1470844800');
INSERT INTO `tl_statistics_fund` VALUES ('2', '1231.00', '1231.00', '0.00', '0.00', '1471017600');
INSERT INTO `tl_statistics_fund` VALUES ('3', '-32.00', '0.00', '32.00', '0.00', '1473436800');
INSERT INTO `tl_statistics_fund` VALUES ('4', '-770.00', '330.00', '1100.00', '221.00', '1474300800');
INSERT INTO `tl_statistics_fund` VALUES ('5', '47.60', '47.60', '0.00', '0.00', '1474387200');

-- ----------------------------
-- Table structure for `tl_statistics_sale`
-- ----------------------------
DROP TABLE IF EXISTS `tl_statistics_sale`;
CREATE TABLE `tl_statistics_sale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品id',
  `sale_num` int(10) unsigned DEFAULT '0' COMMENT '商品销量',
  `sale_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '销售总额',
  `sale_user` int(10) unsigned DEFAULT '0' COMMENT '购买人数',
  `statistics_time` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='商品总销量统计表';

-- ----------------------------
-- Records of tl_statistics_sale
-- ----------------------------
INSERT INTO `tl_statistics_sale` VALUES ('5', '31', '15', '180.00', '3', '1474443697');
INSERT INTO `tl_statistics_sale` VALUES ('6', '41', '6', '115.00', '3', '1474443697');
INSERT INTO `tl_statistics_sale` VALUES ('7', '35', '12', '285.60', '2', '1474443697');
INSERT INTO `tl_statistics_sale` VALUES ('8', '25', '11', '440.00', '2', '1474443698');

-- ----------------------------
-- Table structure for `tl_statistics_sale_day`
-- ----------------------------
DROP TABLE IF EXISTS `tl_statistics_sale_day`;
CREATE TABLE `tl_statistics_sale_day` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品id',
  `sale_num` int(10) unsigned DEFAULT '0' COMMENT '商品销量',
  `sale_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '销售总额',
  `sale_user` int(10) unsigned DEFAULT '0' COMMENT '购买人数',
  `record_time` int(10) unsigned DEFAULT '0' COMMENT '计统时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=489 DEFAULT CHARSET=utf8 COMMENT='商品日销量统计表';

-- ----------------------------
-- Records of tl_statistics_sale_day
-- ----------------------------
INSERT INTO `tl_statistics_sale_day` VALUES ('245', '31', '0', '0.00', '0', '1469980800');
INSERT INTO `tl_statistics_sale_day` VALUES ('246', '41', '0', '0.00', '0', '1469980800');
INSERT INTO `tl_statistics_sale_day` VALUES ('247', '35', '0', '0.00', '0', '1469980800');
INSERT INTO `tl_statistics_sale_day` VALUES ('248', '25', '0', '0.00', '0', '1469980800');
INSERT INTO `tl_statistics_sale_day` VALUES ('249', '31', '0', '0.00', '0', '1470067200');
INSERT INTO `tl_statistics_sale_day` VALUES ('250', '41', '0', '0.00', '0', '1470067200');
INSERT INTO `tl_statistics_sale_day` VALUES ('251', '35', '0', '0.00', '0', '1470067200');
INSERT INTO `tl_statistics_sale_day` VALUES ('252', '25', '0', '0.00', '0', '1470067200');
INSERT INTO `tl_statistics_sale_day` VALUES ('253', '31', '0', '0.00', '0', '1470153600');
INSERT INTO `tl_statistics_sale_day` VALUES ('254', '41', '0', '0.00', '0', '1470153600');
INSERT INTO `tl_statistics_sale_day` VALUES ('255', '35', '0', '0.00', '0', '1470153600');
INSERT INTO `tl_statistics_sale_day` VALUES ('256', '25', '0', '0.00', '0', '1470153600');
INSERT INTO `tl_statistics_sale_day` VALUES ('257', '31', '0', '0.00', '0', '1470240000');
INSERT INTO `tl_statistics_sale_day` VALUES ('258', '41', '0', '0.00', '0', '1470240000');
INSERT INTO `tl_statistics_sale_day` VALUES ('259', '35', '0', '0.00', '0', '1470240000');
INSERT INTO `tl_statistics_sale_day` VALUES ('260', '25', '0', '0.00', '0', '1470240000');
INSERT INTO `tl_statistics_sale_day` VALUES ('261', '31', '0', '0.00', '0', '1470326400');
INSERT INTO `tl_statistics_sale_day` VALUES ('262', '41', '0', '0.00', '0', '1470326400');
INSERT INTO `tl_statistics_sale_day` VALUES ('263', '35', '0', '0.00', '0', '1470326400');
INSERT INTO `tl_statistics_sale_day` VALUES ('264', '25', '0', '0.00', '0', '1470326400');
INSERT INTO `tl_statistics_sale_day` VALUES ('265', '31', '0', '0.00', '0', '1470412800');
INSERT INTO `tl_statistics_sale_day` VALUES ('266', '41', '0', '0.00', '0', '1470412800');
INSERT INTO `tl_statistics_sale_day` VALUES ('267', '35', '0', '0.00', '0', '1470412800');
INSERT INTO `tl_statistics_sale_day` VALUES ('268', '25', '0', '0.00', '0', '1470412800');
INSERT INTO `tl_statistics_sale_day` VALUES ('269', '31', '0', '0.00', '0', '1470499200');
INSERT INTO `tl_statistics_sale_day` VALUES ('270', '41', '0', '0.00', '0', '1470499200');
INSERT INTO `tl_statistics_sale_day` VALUES ('271', '35', '0', '0.00', '0', '1470499200');
INSERT INTO `tl_statistics_sale_day` VALUES ('272', '25', '0', '0.00', '0', '1470499200');
INSERT INTO `tl_statistics_sale_day` VALUES ('273', '31', '0', '0.00', '0', '1470585600');
INSERT INTO `tl_statistics_sale_day` VALUES ('274', '41', '0', '0.00', '0', '1470585600');
INSERT INTO `tl_statistics_sale_day` VALUES ('275', '35', '0', '0.00', '0', '1470585600');
INSERT INTO `tl_statistics_sale_day` VALUES ('276', '25', '0', '0.00', '0', '1470585600');
INSERT INTO `tl_statistics_sale_day` VALUES ('277', '31', '0', '0.00', '0', '1470672000');
INSERT INTO `tl_statistics_sale_day` VALUES ('278', '41', '0', '0.00', '0', '1470672000');
INSERT INTO `tl_statistics_sale_day` VALUES ('279', '35', '0', '0.00', '0', '1470672000');
INSERT INTO `tl_statistics_sale_day` VALUES ('280', '25', '0', '0.00', '0', '1470672000');
INSERT INTO `tl_statistics_sale_day` VALUES ('281', '31', '0', '0.00', '0', '1470758400');
INSERT INTO `tl_statistics_sale_day` VALUES ('282', '41', '0', '0.00', '0', '1470758400');
INSERT INTO `tl_statistics_sale_day` VALUES ('283', '35', '0', '0.00', '0', '1470758400');
INSERT INTO `tl_statistics_sale_day` VALUES ('284', '25', '0', '0.00', '0', '1470758400');
INSERT INTO `tl_statistics_sale_day` VALUES ('285', '31', '0', '0.00', '0', '1470844800');
INSERT INTO `tl_statistics_sale_day` VALUES ('286', '41', '0', '0.00', '0', '1470844800');
INSERT INTO `tl_statistics_sale_day` VALUES ('287', '35', '0', '0.00', '0', '1470844800');
INSERT INTO `tl_statistics_sale_day` VALUES ('288', '25', '0', '0.00', '0', '1470844800');
INSERT INTO `tl_statistics_sale_day` VALUES ('289', '31', '0', '0.00', '0', '1470931200');
INSERT INTO `tl_statistics_sale_day` VALUES ('290', '41', '0', '0.00', '0', '1470931200');
INSERT INTO `tl_statistics_sale_day` VALUES ('291', '35', '0', '0.00', '0', '1470931200');
INSERT INTO `tl_statistics_sale_day` VALUES ('292', '25', '0', '0.00', '0', '1470931200');
INSERT INTO `tl_statistics_sale_day` VALUES ('293', '31', '0', '0.00', '0', '1471017600');
INSERT INTO `tl_statistics_sale_day` VALUES ('294', '41', '0', '0.00', '0', '1471017600');
INSERT INTO `tl_statistics_sale_day` VALUES ('295', '35', '0', '0.00', '0', '1471017600');
INSERT INTO `tl_statistics_sale_day` VALUES ('296', '25', '0', '0.00', '0', '1471017600');
INSERT INTO `tl_statistics_sale_day` VALUES ('297', '31', '0', '0.00', '0', '1471104000');
INSERT INTO `tl_statistics_sale_day` VALUES ('298', '41', '0', '0.00', '0', '1471104000');
INSERT INTO `tl_statistics_sale_day` VALUES ('299', '35', '0', '0.00', '0', '1471104000');
INSERT INTO `tl_statistics_sale_day` VALUES ('300', '25', '0', '0.00', '0', '1471104000');
INSERT INTO `tl_statistics_sale_day` VALUES ('301', '31', '0', '0.00', '0', '1471190400');
INSERT INTO `tl_statistics_sale_day` VALUES ('302', '41', '0', '0.00', '0', '1471190400');
INSERT INTO `tl_statistics_sale_day` VALUES ('303', '35', '0', '0.00', '0', '1471190400');
INSERT INTO `tl_statistics_sale_day` VALUES ('304', '25', '0', '0.00', '0', '1471190400');
INSERT INTO `tl_statistics_sale_day` VALUES ('305', '31', '0', '0.00', '0', '1471276800');
INSERT INTO `tl_statistics_sale_day` VALUES ('306', '41', '0', '0.00', '0', '1471276800');
INSERT INTO `tl_statistics_sale_day` VALUES ('307', '35', '0', '0.00', '0', '1471276800');
INSERT INTO `tl_statistics_sale_day` VALUES ('308', '25', '0', '0.00', '0', '1471276800');
INSERT INTO `tl_statistics_sale_day` VALUES ('309', '31', '0', '0.00', '0', '1471363200');
INSERT INTO `tl_statistics_sale_day` VALUES ('310', '41', '0', '0.00', '0', '1471363200');
INSERT INTO `tl_statistics_sale_day` VALUES ('311', '35', '0', '0.00', '0', '1471363200');
INSERT INTO `tl_statistics_sale_day` VALUES ('312', '25', '0', '0.00', '0', '1471363200');
INSERT INTO `tl_statistics_sale_day` VALUES ('313', '31', '0', '0.00', '0', '1471449600');
INSERT INTO `tl_statistics_sale_day` VALUES ('314', '41', '0', '0.00', '0', '1471449600');
INSERT INTO `tl_statistics_sale_day` VALUES ('315', '35', '0', '0.00', '0', '1471449600');
INSERT INTO `tl_statistics_sale_day` VALUES ('316', '25', '0', '0.00', '0', '1471449600');
INSERT INTO `tl_statistics_sale_day` VALUES ('317', '31', '0', '0.00', '0', '1471536000');
INSERT INTO `tl_statistics_sale_day` VALUES ('318', '41', '0', '0.00', '0', '1471536000');
INSERT INTO `tl_statistics_sale_day` VALUES ('319', '35', '0', '0.00', '0', '1471536000');
INSERT INTO `tl_statistics_sale_day` VALUES ('320', '25', '0', '0.00', '0', '1471536000');
INSERT INTO `tl_statistics_sale_day` VALUES ('321', '31', '0', '0.00', '0', '1471622400');
INSERT INTO `tl_statistics_sale_day` VALUES ('322', '41', '0', '0.00', '0', '1471622400');
INSERT INTO `tl_statistics_sale_day` VALUES ('323', '35', '0', '0.00', '0', '1471622400');
INSERT INTO `tl_statistics_sale_day` VALUES ('324', '25', '0', '0.00', '0', '1471622400');
INSERT INTO `tl_statistics_sale_day` VALUES ('325', '31', '0', '0.00', '0', '1471708800');
INSERT INTO `tl_statistics_sale_day` VALUES ('326', '41', '0', '0.00', '0', '1471708800');
INSERT INTO `tl_statistics_sale_day` VALUES ('327', '35', '0', '0.00', '0', '1471708800');
INSERT INTO `tl_statistics_sale_day` VALUES ('328', '25', '0', '0.00', '0', '1471708800');
INSERT INTO `tl_statistics_sale_day` VALUES ('329', '31', '0', '0.00', '0', '1471795200');
INSERT INTO `tl_statistics_sale_day` VALUES ('330', '41', '0', '0.00', '0', '1471795200');
INSERT INTO `tl_statistics_sale_day` VALUES ('331', '35', '0', '0.00', '0', '1471795200');
INSERT INTO `tl_statistics_sale_day` VALUES ('332', '25', '0', '0.00', '0', '1471795200');
INSERT INTO `tl_statistics_sale_day` VALUES ('333', '31', '0', '0.00', '0', '1471881600');
INSERT INTO `tl_statistics_sale_day` VALUES ('334', '41', '0', '0.00', '0', '1471881600');
INSERT INTO `tl_statistics_sale_day` VALUES ('335', '35', '0', '0.00', '0', '1471881600');
INSERT INTO `tl_statistics_sale_day` VALUES ('336', '25', '0', '0.00', '0', '1471881600');
INSERT INTO `tl_statistics_sale_day` VALUES ('337', '31', '0', '0.00', '0', '1471968000');
INSERT INTO `tl_statistics_sale_day` VALUES ('338', '41', '0', '0.00', '0', '1471968000');
INSERT INTO `tl_statistics_sale_day` VALUES ('339', '35', '0', '0.00', '0', '1471968000');
INSERT INTO `tl_statistics_sale_day` VALUES ('340', '25', '0', '0.00', '0', '1471968000');
INSERT INTO `tl_statistics_sale_day` VALUES ('341', '31', '0', '0.00', '0', '1472054400');
INSERT INTO `tl_statistics_sale_day` VALUES ('342', '41', '0', '0.00', '0', '1472054400');
INSERT INTO `tl_statistics_sale_day` VALUES ('343', '35', '0', '0.00', '0', '1472054400');
INSERT INTO `tl_statistics_sale_day` VALUES ('344', '25', '0', '0.00', '0', '1472054400');
INSERT INTO `tl_statistics_sale_day` VALUES ('345', '31', '0', '0.00', '0', '1472140800');
INSERT INTO `tl_statistics_sale_day` VALUES ('346', '41', '0', '0.00', '0', '1472140800');
INSERT INTO `tl_statistics_sale_day` VALUES ('347', '35', '0', '0.00', '0', '1472140800');
INSERT INTO `tl_statistics_sale_day` VALUES ('348', '25', '0', '0.00', '0', '1472140800');
INSERT INTO `tl_statistics_sale_day` VALUES ('349', '31', '0', '0.00', '0', '1472227200');
INSERT INTO `tl_statistics_sale_day` VALUES ('350', '41', '0', '0.00', '0', '1472227200');
INSERT INTO `tl_statistics_sale_day` VALUES ('351', '35', '0', '0.00', '0', '1472227200');
INSERT INTO `tl_statistics_sale_day` VALUES ('352', '25', '0', '0.00', '0', '1472227200');
INSERT INTO `tl_statistics_sale_day` VALUES ('353', '31', '0', '0.00', '0', '1472313600');
INSERT INTO `tl_statistics_sale_day` VALUES ('354', '41', '0', '0.00', '0', '1472313600');
INSERT INTO `tl_statistics_sale_day` VALUES ('355', '35', '0', '0.00', '0', '1472313600');
INSERT INTO `tl_statistics_sale_day` VALUES ('356', '25', '0', '0.00', '0', '1472313600');
INSERT INTO `tl_statistics_sale_day` VALUES ('357', '31', '0', '0.00', '0', '1472400000');
INSERT INTO `tl_statistics_sale_day` VALUES ('358', '41', '0', '0.00', '0', '1472400000');
INSERT INTO `tl_statistics_sale_day` VALUES ('359', '35', '0', '0.00', '0', '1472400000');
INSERT INTO `tl_statistics_sale_day` VALUES ('360', '25', '0', '0.00', '0', '1472400000');
INSERT INTO `tl_statistics_sale_day` VALUES ('361', '31', '0', '0.00', '0', '1472486400');
INSERT INTO `tl_statistics_sale_day` VALUES ('362', '41', '0', '0.00', '0', '1472486400');
INSERT INTO `tl_statistics_sale_day` VALUES ('363', '35', '0', '0.00', '0', '1472486400');
INSERT INTO `tl_statistics_sale_day` VALUES ('364', '25', '0', '0.00', '0', '1472486400');
INSERT INTO `tl_statistics_sale_day` VALUES ('365', '31', '5', '60.00', '1', '1472572800');
INSERT INTO `tl_statistics_sale_day` VALUES ('366', '41', '3', '46.00', '1', '1472572800');
INSERT INTO `tl_statistics_sale_day` VALUES ('367', '35', '0', '0.00', '0', '1472572800');
INSERT INTO `tl_statistics_sale_day` VALUES ('368', '25', '0', '0.00', '0', '1472572800');
INSERT INTO `tl_statistics_sale_day` VALUES ('369', '31', '10', '120.00', '2', '1472659200');
INSERT INTO `tl_statistics_sale_day` VALUES ('370', '41', '3', '69.00', '2', '1472659200');
INSERT INTO `tl_statistics_sale_day` VALUES ('371', '35', '4', '95.20', '1', '1472659200');
INSERT INTO `tl_statistics_sale_day` VALUES ('372', '25', '0', '0.00', '0', '1472659200');
INSERT INTO `tl_statistics_sale_day` VALUES ('373', '31', '0', '0.00', '0', '1472745600');
INSERT INTO `tl_statistics_sale_day` VALUES ('374', '41', '0', '0.00', '0', '1472745600');
INSERT INTO `tl_statistics_sale_day` VALUES ('375', '35', '0', '0.00', '0', '1472745600');
INSERT INTO `tl_statistics_sale_day` VALUES ('376', '25', '0', '0.00', '0', '1472745600');
INSERT INTO `tl_statistics_sale_day` VALUES ('377', '31', '0', '0.00', '0', '1472832000');
INSERT INTO `tl_statistics_sale_day` VALUES ('378', '41', '0', '0.00', '0', '1472832000');
INSERT INTO `tl_statistics_sale_day` VALUES ('379', '35', '0', '0.00', '0', '1472832000');
INSERT INTO `tl_statistics_sale_day` VALUES ('380', '25', '0', '0.00', '0', '1472832000');
INSERT INTO `tl_statistics_sale_day` VALUES ('381', '31', '0', '0.00', '0', '1472918400');
INSERT INTO `tl_statistics_sale_day` VALUES ('382', '41', '0', '0.00', '0', '1472918400');
INSERT INTO `tl_statistics_sale_day` VALUES ('383', '35', '0', '0.00', '0', '1472918400');
INSERT INTO `tl_statistics_sale_day` VALUES ('384', '25', '0', '0.00', '0', '1472918400');
INSERT INTO `tl_statistics_sale_day` VALUES ('385', '31', '0', '0.00', '0', '1473004800');
INSERT INTO `tl_statistics_sale_day` VALUES ('386', '41', '0', '0.00', '0', '1473004800');
INSERT INTO `tl_statistics_sale_day` VALUES ('387', '35', '0', '0.00', '0', '1473004800');
INSERT INTO `tl_statistics_sale_day` VALUES ('388', '25', '0', '0.00', '0', '1473004800');
INSERT INTO `tl_statistics_sale_day` VALUES ('389', '31', '0', '0.00', '0', '1473091200');
INSERT INTO `tl_statistics_sale_day` VALUES ('390', '41', '0', '0.00', '0', '1473091200');
INSERT INTO `tl_statistics_sale_day` VALUES ('391', '35', '1', '23.80', '1', '1473091200');
INSERT INTO `tl_statistics_sale_day` VALUES ('392', '25', '4', '132.00', '1', '1473091200');
INSERT INTO `tl_statistics_sale_day` VALUES ('393', '31', '0', '0.00', '0', '1473177600');
INSERT INTO `tl_statistics_sale_day` VALUES ('394', '41', '0', '0.00', '0', '1473177600');
INSERT INTO `tl_statistics_sale_day` VALUES ('395', '35', '0', '0.00', '0', '1473177600');
INSERT INTO `tl_statistics_sale_day` VALUES ('396', '25', '0', '0.00', '0', '1473177600');
INSERT INTO `tl_statistics_sale_day` VALUES ('397', '31', '0', '0.00', '0', '1473264000');
INSERT INTO `tl_statistics_sale_day` VALUES ('398', '41', '0', '0.00', '0', '1473264000');
INSERT INTO `tl_statistics_sale_day` VALUES ('399', '35', '0', '0.00', '0', '1473264000');
INSERT INTO `tl_statistics_sale_day` VALUES ('400', '25', '0', '0.00', '0', '1473264000');
INSERT INTO `tl_statistics_sale_day` VALUES ('401', '31', '0', '0.00', '0', '1473350400');
INSERT INTO `tl_statistics_sale_day` VALUES ('402', '41', '0', '0.00', '0', '1473350400');
INSERT INTO `tl_statistics_sale_day` VALUES ('403', '35', '0', '0.00', '0', '1473350400');
INSERT INTO `tl_statistics_sale_day` VALUES ('404', '25', '3', '132.00', '1', '1473350400');
INSERT INTO `tl_statistics_sale_day` VALUES ('405', '31', '0', '0.00', '0', '1473436800');
INSERT INTO `tl_statistics_sale_day` VALUES ('406', '41', '0', '0.00', '0', '1473436800');
INSERT INTO `tl_statistics_sale_day` VALUES ('407', '35', '0', '0.00', '0', '1473436800');
INSERT INTO `tl_statistics_sale_day` VALUES ('408', '25', '0', '0.00', '0', '1473436800');
INSERT INTO `tl_statistics_sale_day` VALUES ('409', '31', '0', '0.00', '0', '1473523200');
INSERT INTO `tl_statistics_sale_day` VALUES ('410', '41', '0', '0.00', '0', '1473523200');
INSERT INTO `tl_statistics_sale_day` VALUES ('411', '35', '0', '0.00', '0', '1473523200');
INSERT INTO `tl_statistics_sale_day` VALUES ('412', '25', '0', '0.00', '0', '1473523200');
INSERT INTO `tl_statistics_sale_day` VALUES ('413', '31', '0', '0.00', '0', '1473609600');
INSERT INTO `tl_statistics_sale_day` VALUES ('414', '41', '0', '0.00', '0', '1473609600');
INSERT INTO `tl_statistics_sale_day` VALUES ('415', '35', '0', '0.00', '0', '1473609600');
INSERT INTO `tl_statistics_sale_day` VALUES ('416', '25', '0', '0.00', '0', '1473609600');
INSERT INTO `tl_statistics_sale_day` VALUES ('417', '31', '0', '0.00', '0', '1473696000');
INSERT INTO `tl_statistics_sale_day` VALUES ('418', '41', '0', '0.00', '0', '1473696000');
INSERT INTO `tl_statistics_sale_day` VALUES ('419', '35', '0', '0.00', '0', '1473696000');
INSERT INTO `tl_statistics_sale_day` VALUES ('420', '25', '2', '88.00', '1', '1473696000');
INSERT INTO `tl_statistics_sale_day` VALUES ('421', '31', '0', '0.00', '0', '1473782400');
INSERT INTO `tl_statistics_sale_day` VALUES ('422', '41', '0', '0.00', '0', '1473782400');
INSERT INTO `tl_statistics_sale_day` VALUES ('423', '35', '0', '0.00', '0', '1473782400');
INSERT INTO `tl_statistics_sale_day` VALUES ('424', '25', '0', '0.00', '0', '1473782400');
INSERT INTO `tl_statistics_sale_day` VALUES ('425', '31', '0', '0.00', '0', '1473868800');
INSERT INTO `tl_statistics_sale_day` VALUES ('426', '41', '0', '0.00', '0', '1473868800');
INSERT INTO `tl_statistics_sale_day` VALUES ('427', '35', '0', '0.00', '0', '1473868800');
INSERT INTO `tl_statistics_sale_day` VALUES ('428', '25', '0', '0.00', '0', '1473868800');
INSERT INTO `tl_statistics_sale_day` VALUES ('429', '31', '0', '0.00', '0', '1473955200');
INSERT INTO `tl_statistics_sale_day` VALUES ('430', '41', '0', '0.00', '0', '1473955200');
INSERT INTO `tl_statistics_sale_day` VALUES ('431', '35', '0', '0.00', '0', '1473955200');
INSERT INTO `tl_statistics_sale_day` VALUES ('432', '25', '0', '0.00', '0', '1473955200');
INSERT INTO `tl_statistics_sale_day` VALUES ('433', '31', '0', '0.00', '0', '1474041600');
INSERT INTO `tl_statistics_sale_day` VALUES ('434', '41', '0', '0.00', '0', '1474041600');
INSERT INTO `tl_statistics_sale_day` VALUES ('435', '35', '0', '0.00', '0', '1474041600');
INSERT INTO `tl_statistics_sale_day` VALUES ('436', '25', '0', '0.00', '0', '1474041600');
INSERT INTO `tl_statistics_sale_day` VALUES ('437', '31', '0', '0.00', '0', '1474128000');
INSERT INTO `tl_statistics_sale_day` VALUES ('438', '41', '0', '0.00', '0', '1474128000');
INSERT INTO `tl_statistics_sale_day` VALUES ('439', '35', '0', '0.00', '0', '1474128000');
INSERT INTO `tl_statistics_sale_day` VALUES ('440', '25', '0', '0.00', '0', '1474128000');
INSERT INTO `tl_statistics_sale_day` VALUES ('441', '31', '0', '0.00', '0', '1474214400');
INSERT INTO `tl_statistics_sale_day` VALUES ('442', '41', '0', '0.00', '0', '1474214400');
INSERT INTO `tl_statistics_sale_day` VALUES ('443', '35', '4', '95.20', '1', '1474214400');
INSERT INTO `tl_statistics_sale_day` VALUES ('444', '25', '0', '0.00', '0', '1474214400');
INSERT INTO `tl_statistics_sale_day` VALUES ('445', '31', '0', '0.00', '0', '1474300800');
INSERT INTO `tl_statistics_sale_day` VALUES ('446', '41', '0', '0.00', '0', '1474300800');
INSERT INTO `tl_statistics_sale_day` VALUES ('447', '35', '3', '71.40', '1', '1474300800');
INSERT INTO `tl_statistics_sale_day` VALUES ('448', '25', '2', '88.00', '1', '1474300800');
INSERT INTO `tl_statistics_sale_day` VALUES ('449', '31', '0', '0.00', '0', '1474387200');
INSERT INTO `tl_statistics_sale_day` VALUES ('450', '41', '0', '0.00', '0', '1474387200');
INSERT INTO `tl_statistics_sale_day` VALUES ('451', '35', '0', '0.00', '0', '1474387200');
INSERT INTO `tl_statistics_sale_day` VALUES ('452', '25', '0', '0.00', '0', '1474387200');
INSERT INTO `tl_statistics_sale_day` VALUES ('453', '31', '0', '0.00', '0', '1474473600');
INSERT INTO `tl_statistics_sale_day` VALUES ('454', '41', '0', '0.00', '0', '1474473600');
INSERT INTO `tl_statistics_sale_day` VALUES ('455', '35', '0', '0.00', '0', '1474473600');
INSERT INTO `tl_statistics_sale_day` VALUES ('456', '25', '0', '0.00', '0', '1474473600');
INSERT INTO `tl_statistics_sale_day` VALUES ('457', '31', '0', '0.00', '0', '1474560000');
INSERT INTO `tl_statistics_sale_day` VALUES ('458', '41', '0', '0.00', '0', '1474560000');
INSERT INTO `tl_statistics_sale_day` VALUES ('459', '35', '0', '0.00', '0', '1474560000');
INSERT INTO `tl_statistics_sale_day` VALUES ('460', '25', '0', '0.00', '0', '1474560000');
INSERT INTO `tl_statistics_sale_day` VALUES ('461', '31', '0', '0.00', '0', '1474646400');
INSERT INTO `tl_statistics_sale_day` VALUES ('462', '41', '0', '0.00', '0', '1474646400');
INSERT INTO `tl_statistics_sale_day` VALUES ('463', '35', '0', '0.00', '0', '1474646400');
INSERT INTO `tl_statistics_sale_day` VALUES ('464', '25', '0', '0.00', '0', '1474646400');
INSERT INTO `tl_statistics_sale_day` VALUES ('465', '31', '0', '0.00', '0', '1474732800');
INSERT INTO `tl_statistics_sale_day` VALUES ('466', '41', '0', '0.00', '0', '1474732800');
INSERT INTO `tl_statistics_sale_day` VALUES ('467', '35', '0', '0.00', '0', '1474732800');
INSERT INTO `tl_statistics_sale_day` VALUES ('468', '25', '0', '0.00', '0', '1474732800');
INSERT INTO `tl_statistics_sale_day` VALUES ('469', '31', '0', '0.00', '0', '1474819200');
INSERT INTO `tl_statistics_sale_day` VALUES ('470', '41', '0', '0.00', '0', '1474819200');
INSERT INTO `tl_statistics_sale_day` VALUES ('471', '35', '0', '0.00', '0', '1474819200');
INSERT INTO `tl_statistics_sale_day` VALUES ('472', '25', '0', '0.00', '0', '1474819200');
INSERT INTO `tl_statistics_sale_day` VALUES ('473', '31', '0', '0.00', '0', '1474905600');
INSERT INTO `tl_statistics_sale_day` VALUES ('474', '41', '0', '0.00', '0', '1474905600');
INSERT INTO `tl_statistics_sale_day` VALUES ('475', '35', '0', '0.00', '0', '1474905600');
INSERT INTO `tl_statistics_sale_day` VALUES ('476', '25', '0', '0.00', '0', '1474905600');
INSERT INTO `tl_statistics_sale_day` VALUES ('477', '31', '0', '0.00', '0', '1474992000');
INSERT INTO `tl_statistics_sale_day` VALUES ('478', '41', '0', '0.00', '0', '1474992000');
INSERT INTO `tl_statistics_sale_day` VALUES ('479', '35', '0', '0.00', '0', '1474992000');
INSERT INTO `tl_statistics_sale_day` VALUES ('480', '25', '0', '0.00', '0', '1474992000');
INSERT INTO `tl_statistics_sale_day` VALUES ('481', '31', '0', '0.00', '0', '1475078400');
INSERT INTO `tl_statistics_sale_day` VALUES ('482', '41', '0', '0.00', '0', '1475078400');
INSERT INTO `tl_statistics_sale_day` VALUES ('483', '35', '0', '0.00', '0', '1475078400');
INSERT INTO `tl_statistics_sale_day` VALUES ('484', '25', '0', '0.00', '0', '1475078400');
INSERT INTO `tl_statistics_sale_day` VALUES ('485', '31', '0', '0.00', '0', '1475164800');
INSERT INTO `tl_statistics_sale_day` VALUES ('486', '41', '0', '0.00', '0', '1475164800');
INSERT INTO `tl_statistics_sale_day` VALUES ('487', '35', '0', '0.00', '0', '1475164800');
INSERT INTO `tl_statistics_sale_day` VALUES ('488', '25', '0', '0.00', '0', '1475164800');

-- ----------------------------
-- Table structure for `tl_statistics_tag`
-- ----------------------------
DROP TABLE IF EXISTS `tl_statistics_tag`;
CREATE TABLE `tl_statistics_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `tag_id` int(10) unsigned DEFAULT '0' COMMENT '标签id',
  `goods_num` int(10) unsigned DEFAULT '0' COMMENT '商品数量统计',
  `statistics_time` int(10) unsigned DEFAULT '0' COMMENT '统计时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品标签统计表';

-- ----------------------------
-- Records of tl_statistics_tag
-- ----------------------------

-- ----------------------------
-- Table structure for `tl_tags`
-- ----------------------------
DROP TABLE IF EXISTS `tl_tags`;
CREATE TABLE `tl_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `tag_name` varchar(255) DEFAULT '' COMMENT '标签名称',
  `state` tinyint(1) unsigned DEFAULT '1' COMMENT '标签状态  0=>删除  1=>正常',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='标签详情表';

-- ----------------------------
-- Records of tl_tags
-- ----------------------------
INSERT INTO `tl_tags` VALUES ('1', '测试标签1', '1', '1461813415', '1461813415');
INSERT INTO `tl_tags` VALUES ('2', '测试标签11', '0', '1461813640', '1461813766');
INSERT INTO `tl_tags` VALUES ('3', '测试标签', '1', '1461813643', '1461813643');
INSERT INTO `tl_tags` VALUES ('4', '测试标签3', '1', '1461813651', '1461813651');
INSERT INTO `tl_tags` VALUES ('5', '测试标签4', '1', '1461813657', '1461813657');
INSERT INTO `tl_tags` VALUES ('6', '测试标签5', '1', '1461813662', '1461813662');
INSERT INTO `tl_tags` VALUES ('7', '测试标签6', '1', '1461813665', '1461813665');
INSERT INTO `tl_tags` VALUES ('8', '测试标签7', '1', '1461813668', '1461813668');
INSERT INTO `tl_tags` VALUES ('9', '测试标签8', '1', '1461813670', '1461813670');
INSERT INTO `tl_tags` VALUES ('10', '测试标签9', '1', '1461813673', '1461813673');
INSERT INTO `tl_tags` VALUES ('11', '测测1', '1', '1461813683', '1461813683');
INSERT INTO `tl_tags` VALUES ('12', '测测2', '1', '1461813686', '1461813686');
INSERT INTO `tl_tags` VALUES ('13', '测测3', '1', '1461813689', '1461813689');
INSERT INTO `tl_tags` VALUES ('15', '测11测5', '1', '1461813694', '1461815634');
INSERT INTO `tl_tags` VALUES ('16', '啊啊啊', '1', '1461813699', '1461813699');
INSERT INTO `tl_tags` VALUES ('17', '啊啊啊1', '1', '1461813701', '1461813701');
INSERT INTO `tl_tags` VALUES ('18', '啊啊啊2', '1', '1461813704', '1461813704');
INSERT INTO `tl_tags` VALUES ('19', '啊啊啊3', '0', '1461813706', '1461813706');
INSERT INTO `tl_tags` VALUES ('20', '啊啊啊4', '1', '1461813709', '1461813709');
INSERT INTO `tl_tags` VALUES ('21', '啊啊啊5', '1', '1461813712', '1461813712');
INSERT INTO `tl_tags` VALUES ('22', '啊啊啊7', '0', '1461813714', '1461813714');
INSERT INTO `tl_tags` VALUES ('23', '啊啊啊11s', '1', '1461813718', '1462952041');
INSERT INTO `tl_tags` VALUES ('24', '22', '1', '1462953309', '1462953309');
INSERT INTO `tl_tags` VALUES ('25', '啊啊啊啊1', '1', '1462953313', '1462954299');
INSERT INTO `tl_tags` VALUES ('26', '667', '1', '1462953738', '1462954239');
INSERT INTO `tl_tags` VALUES ('27', 'aaa6', '1', '1467196285', '1467686856');
INSERT INTO `tl_tags` VALUES ('28', '33', '1', '1467196295', '1467196295');
INSERT INTO `tl_tags` VALUES ('29', 'aaaas', '1', '1467196303', '1467196303');

-- ----------------------------
-- Table structure for `tl_user`
-- ----------------------------
DROP TABLE IF EXISTS `tl_user`;
CREATE TABLE `tl_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` char(32) DEFAULT '' COMMENT '用户名',
  `password` char(32) DEFAULT '' COMMENT '密码',
  `safe_code` char(10) DEFAULT '' COMMENT '安全码',
  `nick_name` varchar(10) DEFAULT NULL COMMENT '用户昵称',
  `mobile` char(20) DEFAULT '' COMMENT '用户手机',
  `state` tinyint(1) unsigned DEFAULT '1' COMMENT '用户状态 0 冻结 1 正常 2 删除',
  `identity` tinyint(1) unsigned DEFAULT '0' COMMENT '身份   0 正常用户  1 后台管理员',
  `reset_code` char(10) DEFAULT '' COMMENT '重置用安全码',
  `active_time` int(10) unsigned DEFAULT '0' COMMENT '最后活跃时间',
  `is_message_tip` tinyint(1) unsigned DEFAULT '0' COMMENT '是否有消息提醒',
  `credit` int(11) DEFAULT '0' COMMENT '用户信用分',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='用户信息表';

-- ----------------------------
-- Records of tl_user
-- ----------------------------
INSERT INTO `tl_user` VALUES ('1', 'yege', 'f50b5cf4313f6d9a6556810adee77837', '94f2325081', '归零者', '18857877819', '1', '1', '12345678', '1474596452', '0', '3', '1458091187', '1473739141');
INSERT INTO `tl_user` VALUES ('2', 'aaa', '4caeb659fd87899ee152a906fa336c03', '86c1ca8f69', '酷炫', '18888888888', '1', '1', '12345678', '1474264585', '1', '1', '1462355520', '1474341288');
INSERT INTO `tl_user` VALUES ('3', '', '73f70a34348e6deacaf56171494032f7', '8f125f641b', null, '18855555000', '1', '0', '12345678', '1472695515', '0', '0', '1462410409', '1472695491');
INSERT INTO `tl_user` VALUES ('4', '', 'ec9e9d4696e3ed4f85dd4ebe74600e52', '5f7de8726f', null, '18866633301', '1', '0', '', '0', '0', '0', '1462410608', '1462410608');
INSERT INTO `tl_user` VALUES ('5', '', 'bb28fb18900df4b7c11e3d003fb270a7', 'dd26cceb0a', null, '18877744111', '1', '0', '3f402e9c', '1467195425', '0', '0', '1462410840', '1463650318');
INSERT INTO `tl_user` VALUES ('6', '', 'bd291bcb861da85ce2d1464ad667ad3c', '01b09892f1', null, '15957555837', '1', '0', '', '0', '0', '0', '1462411140', '1462411140');
INSERT INTO `tl_user` VALUES ('7', '', '909c37b6bfcd475df5a6566405d97ba3', '1a279b12e7', null, '18877777777', '1', '0', 'c901d', '0', '0', '0', '1462867212', '1462867212');
INSERT INTO `tl_user` VALUES ('8', '213123123', '10c0d137e2799ee0cca8f8aa39d65c34', 'ef2be4b851', '313123', '18866666666', '1', '0', 'cae1d916', '1472696875', '0', '1', '1462867275', '1472808146');
INSERT INTO `tl_user` VALUES ('9', '', '275ab56f0069a77fd009e5c26433d9dd', 'be8ef9185c', null, '18865422222', '1', '0', '6ae6a6af', '1464591609', '0', '0', '1464142968', '1464142968');
INSERT INTO `tl_user` VALUES ('10', '', '6f9aef11f7a66b7ff5fc6664f78eaa96', 'a5f392fa3b', null, '18865655999', '1', '0', 'bf463c33', '1464142996', '0', '0', '1464142996', '1464142996');
INSERT INTO `tl_user` VALUES ('11', '', '4a63ac31a485e3f61adbf485595b196d', '31eb6b3ce6', null, '18888855444', '1', '0', '58944bad', '1464330139', '0', '0', '1464250217', '1464250217');
INSERT INTO `tl_user` VALUES ('12', '', 'dcc5208c64fc77aaa2d7215881097805', 'de0ba00724', null, '15957555831', '1', '0', '647cd36c', '1464591527', '0', '0', '1464250352', '1464250352');
INSERT INTO `tl_user` VALUES ('13', '', '91fe6548b49bae852ade724dd5b68ade', '411d0ce842', null, '18855555666', '1', '0', '363872cd', '1470193612', '0', '0', '1464250441', '1464250441');
INSERT INTO `tl_user` VALUES ('14', '', 'f50b5cf4313f6d9a6556810adee77837', '94f2325081', '22335', '18877744110', '1', '0', 'dd7bbee8', '1467196010', '0', '-4', '1467194714', '1473141030');
INSERT INTO `tl_user` VALUES ('15', '', '38c4fc9b882fac43dcb0abb2979338f6', '36cbfb6e4a', null, '18857411000', '1', '0', '66ffe1e1', '1473642965', '0', '0', '1473642965', '1473642965');
INSERT INTO `tl_user` VALUES ('16', '', '6b799c6b93874c5667f7e0007e2692c3', '7ea5084d18', null, '18899966333', '1', '0', '2c586c2f', '1473643313', '0', '0', '1473643313', '1473643313');

-- ----------------------------
-- Table structure for `tl_user_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `tl_user_feedback`;
CREATE TABLE `tl_user_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `type` tinyint(3) unsigned DEFAULT '0' COMMENT '反馈类型 1 => 问题反馈 2 => 订单异议 3 => 意见建议',
  `message` text COMMENT '反馈信息',
  `is_solve` tinyint(4) DEFAULT '0' COMMENT '是否已解决',
  `solve_plan` varchar(255) DEFAULT '' COMMENT '解决方案',
  `solve_time` int(10) unsigned DEFAULT '0' COMMENT '解决时间',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='用户信息反馈记录表';

-- ----------------------------
-- Records of tl_user_feedback
-- ----------------------------
INSERT INTO `tl_user_feedback` VALUES ('1', '1', '2', '订单id:50，订单序列号:D0906147313352919129  1212', '0', '', '1473411266', '1473393185');
INSERT INTO `tl_user_feedback` VALUES ('2', '1', '3', '爱上大声大声道', '0', '', '1473411941', '1473393201');
INSERT INTO `tl_user_feedback` VALUES ('3', '1', '1', '啊实打实大师大师的', '1', 'hhhhhh', '1473412585', '1473393211');
INSERT INTO `tl_user_feedback` VALUES ('4', '1', '1', '大神大神大神大神大神的', '0', '', '1473410507', '1473393222');
INSERT INTO `tl_user_feedback` VALUES ('5', '1', '2', '订单id:50，订单序列号:D0906147313352919129  我已经给钱了', '1', 'OKok', '1473413028', '1473413000');
INSERT INTO `tl_user_feedback` VALUES ('6', '1', '1', '77777', '1', '123123', '1473739193', '1473739178');
INSERT INTO `tl_user_feedback` VALUES ('7', '2', '2', '订单：D0919147426708754263  不对啊', '1', '3123123', '1474267486', '1474267385');
INSERT INTO `tl_user_feedback` VALUES ('8', '1', '2', '订单：D0921147442411122598  不要了', '1', 'ok', '1474424553', '1474424523');

-- ----------------------------
-- Table structure for `tl_user_message`
-- ----------------------------
DROP TABLE IF EXISTS `tl_user_message`;
CREATE TABLE `tl_user_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `remark` varchar(255) DEFAULT '' COMMENT '操作信息',
  `is_show` tinyint(1) unsigned DEFAULT '0' COMMENT '是否需要展示给前台',
  `is_delete` tinyint(1) unsigned DEFAULT '0' COMMENT '是否被删除',
  `warning` tinyint(1) unsigned DEFAULT '0' COMMENT '警告中的用户信息',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `deletetime` int(10) unsigned DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8 COMMENT='用户消息表';

-- ----------------------------
-- Records of tl_user_message
-- ----------------------------
INSERT INTO `tl_user_message` VALUES ('6', '8', '因：456456 ，用户被暂时冻结，部分功能受限。', '0', '0', '0', '1463108485', '0');
INSERT INTO `tl_user_message` VALUES ('7', '8', '因：456456 ，用户被恢复正常状态。', '1', '0', '0', '1463108489', '0');
INSERT INTO `tl_user_message` VALUES ('8', '8', '因：456456 ，用户被删除。', '0', '0', '0', '1463108494', '0');
INSERT INTO `tl_user_message` VALUES ('9', '8', '因：456456 ，用户被恢复正常状态。', '0', '0', '0', '1463108498', '0');
INSERT INTO `tl_user_message` VALUES ('10', '8', '因：456456 ，用户被暂时冻结，部分功能受限。', '0', '0', '0', '1463108501', '0');
INSERT INTO `tl_user_message` VALUES ('11', '8', '后台修改了用户信息，nick_name:313123,mobile:18866666666', '0', '0', '0', '1463108512', '0');
INSERT INTO `tl_user_message` VALUES ('12', '8', '后台修改了用户信息，nick_name:313123,mobile:18866666666', '0', '0', '0', '1463624181', '0');
INSERT INTO `tl_user_message` VALUES ('13', '8', '用户被恢复正常状态。', '1', '0', '0', '1463624191', '0');
INSERT INTO `tl_user_message` VALUES ('14', '8', '因：逗 ，用户被暂时冻结，部分功能受限。', '1', '0', '0', '1463624212', '0');
INSERT INTO `tl_user_message` VALUES ('15', '8', '因：不逗 ，用户被恢复正常状态。', '1', '0', '0', '1463624228', '0');
INSERT INTO `tl_user_message` VALUES ('16', '8', '因：不逗 ，用户被删除。', '1', '0', '0', '1463624236', '0');
INSERT INTO `tl_user_message` VALUES ('17', '8', '因：不1 逗 ，用户被恢复正常状态。', '1', '0', '0', '1463624259', '0');
INSERT INTO `tl_user_message` VALUES ('18', '5', '后台重置了用户的重置用安全码，现在用户的重置用安全码为:3f402e9c', '0', '0', '0', '1463650318', '0');
INSERT INTO `tl_user_message` VALUES ('19', '9', '注册成功，之后的服务会默认提供给 手机号：188****2222 可以在会员中心更改绑定手机', '1', '0', '0', '1464142968', '0');
INSERT INTO `tl_user_message` VALUES ('20', '10', '注册成功，之后的服务会默认提供给 手机号：188****5999 可以在会员中心更改绑定手机', '1', '0', '0', '1464142996', '0');
INSERT INTO `tl_user_message` VALUES ('21', '11', '注册成功，之后的服务会默认提供给 手机号：188****5444 可以在会员中心更改绑定手机', '1', '0', '0', '1464250218', '0');
INSERT INTO `tl_user_message` VALUES ('22', '12', '注册成功，之后的服务会默认提供给 手机号：159****5831 可以在会员中心更改绑定手机', '1', '0', '0', '1464250352', '0');
INSERT INTO `tl_user_message` VALUES ('23', '13', '注册成功，之后的服务会默认提供给 手机号：188****5666 可以在会员中心更改绑定手机', '1', '0', '0', '1464250441', '0');
INSERT INTO `tl_user_message` VALUES ('24', '14', '注册成功，之后的服务会默认提供给 手机号：188****4110 可以在会员中心更改绑定手机', '1', '0', '0', '1467194714', '0');
INSERT INTO `tl_user_message` VALUES ('25', '14', '后台修改了用户信息，nick_name:22335,mobile:18877744110', '0', '0', '0', '1467249412', '0');
INSERT INTO `tl_user_message` VALUES ('27', '14', '因：1312321 ，用户被恢复正常状态。', '1', '0', '0', '1467249441', '0');
INSERT INTO `tl_user_message` VALUES ('28', '14', '因：1312321 ，用户被暂时冻结，部分功能受限。', '1', '0', '0', '1467249474', '0');
INSERT INTO `tl_user_message` VALUES ('29', '14', '因：aa ，用户被恢复正常状态。', '1', '0', '0', '1467249498', '0');
INSERT INTO `tl_user_message` VALUES ('30', '14', '后台修改了用户的身份，现在用户身份为:1(管理员)', '0', '0', '0', '1467249508', '0');
INSERT INTO `tl_user_message` VALUES ('31', '14', '后台修改了用户的身份，现在用户身份为:0(用户)', '0', '0', '0', '1467249521', '0');
INSERT INTO `tl_user_message` VALUES ('32', '14', '后台重置了用户的重置用安全码，现在用户的重置用安全码为:dd7bbee8', '0', '0', '0', '1467259183', '0');
INSERT INTO `tl_user_message` VALUES ('33', '1', '因：1212 ，用户被暂时冻结，部分功能受限。', '1', '0', '0', '1471686114', '0');
INSERT INTO `tl_user_message` VALUES ('34', '1', '因：1212 ，用户被恢复正常状态。', '1', '0', '0', '1471686781', '0');
INSERT INTO `tl_user_message` VALUES ('35', '1', '2016-09-02 13:53:02您的订单已开始配送。', '1', '0', '0', '1472795582', '0');
INSERT INTO `tl_user_message` VALUES ('36', '2', '2016-09-02 13:53:02您的订单已开始配送。', '1', '0', '0', '1472795582', '0');
INSERT INTO `tl_user_message` VALUES ('37', '3', '2016-09-02 13:53:02您的订单已开始配送。', '1', '0', '0', '1472795582', '0');
INSERT INTO `tl_user_message` VALUES ('38', '2', '2016-09-02 13:53:02您的订单已开始配送。', '1', '0', '0', '1472795582', '0');
INSERT INTO `tl_user_message` VALUES ('39', '1', '2016-09-02 13:53:03您的订单已开始配送。', '1', '0', '0', '1472795583', '0');
INSERT INTO `tl_user_message` VALUES ('40', '1', '2016-09-02 13:53:03您的订单已开始配送。', '1', '0', '0', '1472795583', '0');
INSERT INTO `tl_user_message` VALUES ('41', '1', '您的订单在 2016-09-02 13:56:13 被确认已付款。', '1', '0', '0', '1472795773', '0');
INSERT INTO `tl_user_message` VALUES ('42', '1', '您的订单在 2016-09-02 13:57:37 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1472795857', '0');
INSERT INTO `tl_user_message` VALUES ('43', '1', '2016-09-02 13:57:44您的订单已开始配送。', '1', '0', '0', '1472795864', '0');
INSERT INTO `tl_user_message` VALUES ('44', '2016', '1', '0', '0', '0', '1472805253', '0');
INSERT INTO `tl_user_message` VALUES ('45', '1', '您的订单在 2016-09-02 16:37:30 被确认已付款。', '1', '0', '0', '1472805450', '0');
INSERT INTO `tl_user_message` VALUES ('46', '2016', '1', '0', '0', '0', '1472805459', '0');
INSERT INTO `tl_user_message` VALUES ('47', '2016', '1', '0', '0', '0', '1472806429', '0');
INSERT INTO `tl_user_message` VALUES ('48', '2016', '1', '0', '0', '0', '1472806429', '0');
INSERT INTO `tl_user_message` VALUES ('49', '2016', '1', '0', '0', '0', '1472806430', '0');
INSERT INTO `tl_user_message` VALUES ('50', '3', '您的订单在 2016-09-02 16:56:39 被确认已付款。', '1', '0', '0', '1472806599', '0');
INSERT INTO `tl_user_message` VALUES ('51', '1', '您的订单在 2016-09-02 16:56:44 被确认已付款。', '1', '0', '0', '1472806604', '0');
INSERT INTO `tl_user_message` VALUES ('52', '2016', '1', '0', '0', '0', '1472806615', '0');
INSERT INTO `tl_user_message` VALUES ('53', '2016', '1', '0', '0', '0', '1472806615', '0');
INSERT INTO `tl_user_message` VALUES ('54', '8', '您的订单在 2016-09-02 17:21:58 被确认已付款。', '1', '0', '0', '1472808118', '0');
INSERT INTO `tl_user_message` VALUES ('55', '8', '您的订单在 2016-09-02 17:21:58 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1472808118', '0');
INSERT INTO `tl_user_message` VALUES ('56', '8', '2016-09-02 17:22:07您的订单已开始配送。', '1', '0', '0', '1472808127', '0');
INSERT INTO `tl_user_message` VALUES ('57', '2016', '1', '0', '0', '0', '1472808146', '0');
INSERT INTO `tl_user_message` VALUES ('58', '1', '您的订单 D0901147271725471888 因：“过期了”，被关闭。', '1', '0', '0', '1473126556', '0');
INSERT INTO `tl_user_message` VALUES ('59', '1', '您的订单 D0901147271727143997 因：“312313”，已退款。', '1', '0', '0', '1473130939', '0');
INSERT INTO `tl_user_message` VALUES ('60', '2', '您的订单 D0901147271189328890 因：“13123”，已退款。', '1', '0', '0', '1473130960', '0');
INSERT INTO `tl_user_message` VALUES ('61', '1', '您的订单 D0906147313134422889 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1473131394', '0');
INSERT INTO `tl_user_message` VALUES ('62', '1', '您的订单 D0906147313134422889 因：“不给了”，被关闭。', '1', '0', '0', '1473131461', '0');
INSERT INTO `tl_user_message` VALUES ('63', '1', '您的订单 D0906147313132283861 被确认已付款。', '1', '0', '0', '1473131504', '0');
INSERT INTO `tl_user_message` VALUES ('64', '1', '您的订单 D0906147313132283861 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1473131504', '0');
INSERT INTO `tl_user_message` VALUES ('65', '1', '您的订单 D0906147313132283861 已开始配送。', '1', '0', '0', '1473131527', '0');
INSERT INTO `tl_user_message` VALUES ('66', '1', '您的订单 D0906147313161280603 被确认已付款。', '1', '0', '0', '1473131626', '0');
INSERT INTO `tl_user_message` VALUES ('67', '1', '您的订单 D0906147313161280603 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1473131626', '0');
INSERT INTO `tl_user_message` VALUES ('68', '1', '您的订单 D0906147313161280603 已开始配送。', '1', '0', '0', '1473131638', '0');
INSERT INTO `tl_user_message` VALUES ('69', '1', '您的订单 D0906147313161280603 因：“56757”，已退款。', '1', '0', '0', '1473131647', '0');
INSERT INTO `tl_user_message` VALUES ('70', '1', '您的订单 D0906147313316226575 因：“7y7777”，被关闭。', '1', '0', '0', '1473133175', '0');
INSERT INTO `tl_user_message` VALUES ('71', '14', '112', '0', '0', '0', '1473140601', '0');
INSERT INTO `tl_user_message` VALUES ('72', '14', '因：12删除 用户的信用分发生改变，改变值：-6', '0', '0', '0', '1473141030', '0');
INSERT INTO `tl_user_message` VALUES ('73', '1', '您 2016-09-09 11:53:21 提交的问题已经解决，点击查看详情', '1', '0', '0', '1473411942', '0');
INSERT INTO `tl_user_message` VALUES ('74', '1', '您 2016-09-09 11:53:31 提交的问题已经解决，点击查看详情', '1', '0', '0', '1473412494', '0');
INSERT INTO `tl_user_message` VALUES ('75', '1', '您 2016-09-09 11:53:31 提交的问题已经解决，<a target=\'_blank\' href=\'/Home/user/showFeedback/id/3\' >点击查看详情</a>', '1', '0', '0', '1473412585', '0');
INSERT INTO `tl_user_message` VALUES ('76', '1', '您的订单 D0909147340498062421 被确认已付款。', '1', '0', '0', '1473412924', '0');
INSERT INTO `tl_user_message` VALUES ('77', '1', '您的订单 D0909147340498062421 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1473412924', '0');
INSERT INTO `tl_user_message` VALUES ('78', '1', '您的订单 D0906147313352919129 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1473412930', '0');
INSERT INTO `tl_user_message` VALUES ('79', '1', '您的订单 D0909147340498062421 已开始配送。', '1', '0', '0', '1473412950', '0');
INSERT INTO `tl_user_message` VALUES ('80', '1', '您的订单 D0906147313352919129 已开始配送。', '1', '0', '0', '1473412950', '0');
INSERT INTO `tl_user_message` VALUES ('81', '1', '您的订单 D0909147340498062421 已完成。', '1', '0', '0', '1473412954', '0');
INSERT INTO `tl_user_message` VALUES ('82', '1', '订单 D0906147313352919129 进入待结算状态，请及时为订单付款。付款方式可在 订单详情 中查看。', '1', '0', '0', '1473412954', '0');
INSERT INTO `tl_user_message` VALUES ('83', '1', '您 2016-09-09 17:23:20 提交的问题已经解决，<a target=\'_blank\' href=\'/Home/user/showFeedback/id/5\' >点击查看详情</a>', '1', '0', '0', '1473413028', '0');
INSERT INTO `tl_user_message` VALUES ('84', '1', '您的订单 D0906147313352919129 被确认已付款。', '1', '0', '0', '1473413039', '0');
INSERT INTO `tl_user_message` VALUES ('85', '1', '您的订单 D0906147313352919129 已完成。', '1', '0', '0', '1473413060', '0');
INSERT INTO `tl_user_message` VALUES ('86', '15', '注册成功，之后的服务只会提供给 手机号：188****1000 如需更改，请联系客服 QQ 3219939283', '1', '0', '0', '1473642965', '0');
INSERT INTO `tl_user_message` VALUES ('87', '16', '注册成功，您的重置用安全码是：2c586c2f，之后的服务只会提供给 手机号：188****6333 如需更改，请联系客服 QQ 3219939283', '1', '0', '0', '1473643313', '0');
INSERT INTO `tl_user_message` VALUES ('88', '1', '您的订单 D0913147373908769117 被确认已付款。', '1', '0', '0', '1473739120', '0');
INSERT INTO `tl_user_message` VALUES ('89', '1', '您的订单 D0913147373908769117 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1473739120', '0');
INSERT INTO `tl_user_message` VALUES ('90', '1', '您的订单 D0913147373908769117 已开始配送。', '1', '0', '0', '1473739132', '0');
INSERT INTO `tl_user_message` VALUES ('91', '1', '您的订单 D0913147373908769117 已完成。', '1', '0', '0', '1473739141', '0');
INSERT INTO `tl_user_message` VALUES ('92', '1', '您 2016-09-13 11:59:38 提交的问题已经解决，<a target=\'_blank\' href=\'/Home/UserCenter/showFeedback/id/6\' >点击查看详情</a>', '1', '0', '0', '1473739193', '0');
INSERT INTO `tl_user_message` VALUES ('93', '2', '您的订单 D0919147426708754263 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1474267188', '0');
INSERT INTO `tl_user_message` VALUES ('94', '2', '您的订单 D0919147426708754263 已开始配送。', '1', '0', '0', '1474267201', '0');
INSERT INTO `tl_user_message` VALUES ('95', '2', '订单 D0919147426708754263 进入待结算状态，请及时为订单付款。付款方式可在 订单详情 中查看。', '1', '0', '0', '1474267223', '0');
INSERT INTO `tl_user_message` VALUES ('96', '2', '您 2016-09-19 14:43:05 提交的问题已经解决，<a target=\'_blank\' href=\'/Home/UserCenter/showFeedback/id/7\' >点击查看详情</a>', '1', '0', '0', '1474267486', '0');
INSERT INTO `tl_user_message` VALUES ('97', '2', '您的订单 D0919147426708754263 被确认已付款。', '1', '0', '0', '1474267521', '0');
INSERT INTO `tl_user_message` VALUES ('98', '2', '您的订单 D0919147426708754263 已完成。', '1', '0', '0', '1474267534', '0');
INSERT INTO `tl_user_message` VALUES ('99', '2', '您的订单 D0920147434098042898 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1474341002', '0');
INSERT INTO `tl_user_message` VALUES ('100', '2', '您的订单 D0920147434098042898 已开始配送。', '1', '0', '0', '1474341012', '0');
INSERT INTO `tl_user_message` VALUES ('101', '2', '订单 D0920147434098042898 进入待结算状态，请及时为订单付款。付款方式可在 订单详情 中查看。', '1', '0', '0', '1474341018', '0');
INSERT INTO `tl_user_message` VALUES ('102', '2', '您的订单 D0920147434098042898 被确认已付款。', '1', '0', '0', '1474341041', '0');
INSERT INTO `tl_user_message` VALUES ('103', '2', '您的订单 D0920147434098042898 已完成。', '1', '0', '0', '1474341045', '0');
INSERT INTO `tl_user_message` VALUES ('104', '2', '您的订单 D0920147434119782306 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1474341218', '0');
INSERT INTO `tl_user_message` VALUES ('105', '2', '您的订单 D0920147434119782306 已开始配送。', '1', '0', '0', '1474341223', '0');
INSERT INTO `tl_user_message` VALUES ('106', '2', '订单 D0920147434119782306 进入待结算状态，请及时为订单付款。付款方式可在 订单详情 中查看。', '1', '0', '0', '1474341228', '0');
INSERT INTO `tl_user_message` VALUES ('107', '2', '您的订单 D0920147434119782306 被确认已付款。', '1', '0', '0', '1474341245', '0');
INSERT INTO `tl_user_message` VALUES ('108', '2', '您的订单 D0920147434126886613 被确认已付款。', '1', '0', '0', '1474341279', '0');
INSERT INTO `tl_user_message` VALUES ('109', '2', '您的订单 D0920147434126886613 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1474341279', '0');
INSERT INTO `tl_user_message` VALUES ('110', '2', '您的订单 D0920147434126886613 已开始配送。', '1', '0', '0', '1474341283', '0');
INSERT INTO `tl_user_message` VALUES ('111', '2', '您的订单 D0920147434126886613 已完成。', '1', '0', '0', '1474341288', '0');
INSERT INTO `tl_user_message` VALUES ('112', '2', '您的订单 D0920147434119782306 已完成。', '1', '0', '0', '1474341299', '0');
INSERT INTO `tl_user_message` VALUES ('113', '1', '您的订单 D0920147436447257765 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1474366367', '0');
INSERT INTO `tl_user_message` VALUES ('114', '1', '您的订单 D0920147436447257765 已开始配送。', '1', '0', '0', '1474366389', '0');
INSERT INTO `tl_user_message` VALUES ('115', '1', '订单 D0920147436447257765 进入待结算状态，请及时为订单付款。付款方式可在 订单详情 中查看。', '1', '0', '0', '1474366414', '0');
INSERT INTO `tl_user_message` VALUES ('116', '1', '您的订单 D0920147436447257765 被确认已付款。', '1', '0', '0', '1474421579', '0');
INSERT INTO `tl_user_message` VALUES ('117', '1', '您的订单 D0920147436447257765 已完成。', '1', '0', '0', '1474421587', '0');
INSERT INTO `tl_user_message` VALUES ('118', '1', '您的订单 D0920147436447257765 因：“觉得不行”，已退款。', '1', '0', '0', '1474422638', '0');
INSERT INTO `tl_user_message` VALUES ('119', '1', '您的订单 D0920147436447257765 因：“觉得不行”，已退款。', '1', '0', '0', '1474422647', '0');
INSERT INTO `tl_user_message` VALUES ('120', '1', '您的订单 D0921147442411122598 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1474424505', '0');
INSERT INTO `tl_user_message` VALUES ('121', '1', '您 2016-09-21 10:22:03 提交的问题已经解决，<a target=\'_blank\' href=\'/Home/UserCenter/showFeedback/id/8\' >点击查看详情</a>', '1', '0', '0', '1474424553', '0');
INSERT INTO `tl_user_message` VALUES ('122', '1', '您的订单 D0921147442411122598 因：“yongh buyao l”，被关闭。', '1', '0', '0', '1474424572', '0');
INSERT INTO `tl_user_message` VALUES ('123', '2', '您的订单 D0923147460148442508 被确认通过，订单正处于待发货状态。', '1', '0', '0', '1474610803', '0');
INSERT INTO `tl_user_message` VALUES ('124', '2', '您的订单 D0923147460148442508 已开始配送。', '1', '0', '0', '1474610830', '0');
INSERT INTO `tl_user_message` VALUES ('125', '2', '订单 D0923147460148442508 进入待结算状态，请及时为订单付款。付款方式可在 订单详情 中查看。', '1', '0', '0', '1474610837', '0');
INSERT INTO `tl_user_message` VALUES ('126', '2', '您的订单 D0923147460148442508 被确认已付款。', '1', '0', '0', '1474610852', '0');
INSERT INTO `tl_user_message` VALUES ('127', '2', '您的订单 D0923147460148442508 已完成。', '1', '0', '0', '1474610856', '0');

-- ----------------------------
-- Table structure for `tl_user_points`
-- ----------------------------
DROP TABLE IF EXISTS `tl_user_points`;
CREATE TABLE `tl_user_points` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `points` int(11) DEFAULT '0' COMMENT '用户积分',
  `updatetime` int(10) unsigned DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='用户积分表';

-- ----------------------------
-- Records of tl_user_points
-- ----------------------------
INSERT INTO `tl_user_points` VALUES ('2', '2', '6', '1474264662');
INSERT INTO `tl_user_points` VALUES ('3', '3', '3', '1462410409');
INSERT INTO `tl_user_points` VALUES ('4', '4', '3', '1462410608');
INSERT INTO `tl_user_points` VALUES ('5', '5', '3', '1462410840');
INSERT INTO `tl_user_points` VALUES ('6', '6', '3', '1462411141');
INSERT INTO `tl_user_points` VALUES ('7', '7', '5', '1462867212');
INSERT INTO `tl_user_points` VALUES ('8', '8', '0', '1463728953');
INSERT INTO `tl_user_points` VALUES ('9', '9', '6', '1464591615');
INSERT INTO `tl_user_points` VALUES ('10', '10', '5', '1464142996');
INSERT INTO `tl_user_points` VALUES ('11', '1', '0', '1474364472');
INSERT INTO `tl_user_points` VALUES ('12', '11', '6', '1464250317');
INSERT INTO `tl_user_points` VALUES ('13', '12', '8', '1464330621');
INSERT INTO `tl_user_points` VALUES ('14', '13', '6', '1464250460');
INSERT INTO `tl_user_points` VALUES ('15', '14', '8', '1467265883');
INSERT INTO `tl_user_points` VALUES ('16', '15', '5', '1473642965');
INSERT INTO `tl_user_points` VALUES ('17', '16', '5', '1473643313');

-- ----------------------------
-- Table structure for `tl_user_points_log`
-- ----------------------------
DROP TABLE IF EXISTS `tl_user_points_log`;
CREATE TABLE `tl_user_points_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `log` varchar(255) DEFAULT '' COMMENT '积分日志',
  `remark` varchar(255) DEFAULT '' COMMENT '备注说明',
  `points` int(11) DEFAULT '0' COMMENT '相关积分',
  `result_points` int(11) DEFAULT '0' COMMENT '积分结果',
  `operation_tab` varchar(50) DEFAULT '' COMMENT '操作标记',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 COMMENT='用户积分日志表';

-- ----------------------------
-- Records of tl_user_points_log
-- ----------------------------
INSERT INTO `tl_user_points_log` VALUES ('19', '2', '新用户注册', '欢迎新用户，注册即送5点积分', '3', '3', 'new_user_register', '1462355520');
INSERT INTO `tl_user_points_log` VALUES ('20', '2', '新用户注册活动', '活动期间再送2点', '2', '5', 'new_user_register_activity', '1462355520');
INSERT INTO `tl_user_points_log` VALUES ('21', '3', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1462410409');
INSERT INTO `tl_user_points_log` VALUES ('22', '4', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1462410609');
INSERT INTO `tl_user_points_log` VALUES ('23', '5', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1462410840');
INSERT INTO `tl_user_points_log` VALUES ('24', '6', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1462411141');
INSERT INTO `tl_user_points_log` VALUES ('25', '7', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1462867212');
INSERT INTO `tl_user_points_log` VALUES ('26', '7', '新用户注册活动', '活动期间再送2点', '2', '5', 'new_user_register_activity', '1462867212');
INSERT INTO `tl_user_points_log` VALUES ('27', '8', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1462867275');
INSERT INTO `tl_user_points_log` VALUES ('28', '8', '新用户注册活动', '活动期间再送2点', '2', '5', 'new_user_register_activity', '1462867275');
INSERT INTO `tl_user_points_log` VALUES ('29', '8', '系统操作', '系统操作', '123123', '123128', 'admin_update_point', '1463727759');
INSERT INTO `tl_user_points_log` VALUES ('30', '8', '系统操作', '333', '-11112', '112016', 'admin_update_point', '1463727850');
INSERT INTO `tl_user_points_log` VALUES ('31', '8', '系统操作', '333', '112016', '224032', 'admin_update_point', '1463728932');
INSERT INTO `tl_user_points_log` VALUES ('32', '8', '系统操作', 'asdasdasdasdasd', '-224032', '0', 'admin_update_point', '1463728953');
INSERT INTO `tl_user_points_log` VALUES ('33', '9', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1464142968');
INSERT INTO `tl_user_points_log` VALUES ('34', '9', '新用户注册活动', '活动期间再送2点', '2', '5', 'new_user_register_activity', '1464142968');
INSERT INTO `tl_user_points_log` VALUES ('35', '10', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1464142996');
INSERT INTO `tl_user_points_log` VALUES ('36', '10', '新用户注册活动', '活动期间再送2点', '2', '5', 'new_user_register_activity', '1464142996');
INSERT INTO `tl_user_points_log` VALUES ('37', '1', '每日答题活动', '完成今日答题，获得1点积分', '1', '1', 'answer_question_every_day_activity', '1464250098');
INSERT INTO `tl_user_points_log` VALUES ('38', '11', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1464250217');
INSERT INTO `tl_user_points_log` VALUES ('39', '11', '新用户注册活动', '活动期间再送2点', '2', '5', 'new_user_register_activity', '1464250218');
INSERT INTO `tl_user_points_log` VALUES ('40', '11', '每日答题活动', '完成今日答题，获得1点积分', '1', '6', 'answer_question_every_day_activity', '1464250317');
INSERT INTO `tl_user_points_log` VALUES ('41', '12', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1464250352');
INSERT INTO `tl_user_points_log` VALUES ('42', '12', '新用户注册活动', '活动期间再送2点', '2', '5', 'new_user_register_activity', '1464250352');
INSERT INTO `tl_user_points_log` VALUES ('43', '13', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1464250441');
INSERT INTO `tl_user_points_log` VALUES ('44', '13', '新用户注册活动', '活动期间再送2点', '2', '5', 'new_user_register_activity', '1464250441');
INSERT INTO `tl_user_points_log` VALUES ('45', '13', '每日答题活动', '完成今日答题，获得1点积分', '1', '6', 'answer_question_every_day_activity', '1464250460');
INSERT INTO `tl_user_points_log` VALUES ('46', '12', '每日答题活动', '完成今日答题，获得1点积分', '1', '6', 'answer_question_every_day_activity', '1464250490');
INSERT INTO `tl_user_points_log` VALUES ('47', '1', '每日答题活动', '完成今日答题，获得1点积分', '1', '2', 'answer_question_every_day_activity', '1464321353');
INSERT INTO `tl_user_points_log` VALUES ('48', '1', '每日答题活动', '完成今日答题，获得1点积分', '1', '3', 'answer_question_every_day_activity', '1464321514');
INSERT INTO `tl_user_points_log` VALUES ('49', '12', '每日答题活动', '完成今日答题，获得1点积分', '1', '7', 'answer_question_every_day_activity', '1464330033');
INSERT INTO `tl_user_points_log` VALUES ('50', '1', '每日答题活动', '完成今日答题，获得1点积分', '1', '4', 'answer_question_every_day_activity', '1464330445');
INSERT INTO `tl_user_points_log` VALUES ('51', '12', '每日答题活动', '完成今日答题，获得1点积分', '1', '8', 'answer_question_every_day_activity', '1464330621');
INSERT INTO `tl_user_points_log` VALUES ('52', '1', '每日答题活动', '完成今日答题，获得1点积分', '1', '5', 'answer_question_every_day_activity', '1464330628');
INSERT INTO `tl_user_points_log` VALUES ('53', '9', '每日答题活动', '完成今日答题，获得1点积分', '1', '6', 'answer_question_every_day_activity', '1464591615');
INSERT INTO `tl_user_points_log` VALUES ('54', '1', '每日答题活动', '完成今日答题，获得1点积分', '1', '6', 'answer_question_every_day_activity', '1464592223');
INSERT INTO `tl_user_points_log` VALUES ('55', '1', '每日答题活动', '完成今日答题，获得1点积分', '1', '7', 'answer_question_every_day_activity', '1464852787');
INSERT INTO `tl_user_points_log` VALUES ('56', '14', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1467194714');
INSERT INTO `tl_user_points_log` VALUES ('57', '14', '新用户注册活动', '活动期间再送2点', '2', '5', 'new_user_register_activity', '1467194714');
INSERT INTO `tl_user_points_log` VALUES ('58', '14', '每日答题活动', '完成今日答题，获得1点积分', '1', '6', 'answer_question_every_day_activity', '1467196090');
INSERT INTO `tl_user_points_log` VALUES ('59', '14', '系统操作', '12', '2', '8', 'admin_update_point', '1467265883');
INSERT INTO `tl_user_points_log` VALUES ('60', '1', '每日答题活动', '完成今日答题，获得1点积分', '1', '8', 'answer_question_every_day_activity', '1468391543');
INSERT INTO `tl_user_points_log` VALUES ('61', '1', '系统操作', '送你了', '22', '30', 'admin_update_point', '1472201461');
INSERT INTO `tl_user_points_log` VALUES ('62', '1', '由订单 D0826147220147092202 消费。', '2016-08-26 16:51:10生成订单 D0826147220147092202 ，用户使用积分：22。', '-22', '8', 'goods_consume', '1472201470');
INSERT INTO `tl_user_points_log` VALUES ('63', '1', '由订单 D0831147262638417391 消费', '2016-08-31 14:53:04生成订单 D0831147262638417391 ，用户使用积分：5', '-5', '3', 'goods_consume', '1472626384');
INSERT INTO `tl_user_points_log` VALUES ('64', '1', '取消订单 D0831147262638417391 ，恢复积分', '2016-08-31 14:53:14取消订单 D0831147262638417391 ，用户恢复积分：5', '5', '8', 'order_cancel', '1472626394');
INSERT INTO `tl_user_points_log` VALUES ('65', '1', '由订单 D0831147262645724062 消费', '2016-08-31 14:54:17生成订单 D0831147262645724062 ，用户使用积分：5', '-5', '3', 'goods_consume', '1472626457');
INSERT INTO `tl_user_points_log` VALUES ('66', '1', '取消订单 D0831147262645724062 ，恢复积分', '2016-08-31 14:55:13取消订单 D0831147262645724062 ，用户恢复积分：5', '5', '8', 'order_cancel', '1472626513');
INSERT INTO `tl_user_points_log` VALUES ('67', '1', '由订单 D0831147262653246719 消费', '2016-08-31 14:55:32生成订单 D0831147262653246719 ，用户使用积分：5', '-5', '3', 'goods_consume', '1472626532');
INSERT INTO `tl_user_points_log` VALUES ('68', '1', '由订单 D0906147313132283861 消费', '2016-09-06 11:08:42生成订单 D0906147313132283861 ，用户使用积分：2', '-2', '1', 'goods_consume', '1473131322');
INSERT INTO `tl_user_points_log` VALUES ('69', '15', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1473642965');
INSERT INTO `tl_user_points_log` VALUES ('70', '15', '新用户注册活动', '活动期间再送2点', '2', '5', 'new_user_register_activity', '1473642965');
INSERT INTO `tl_user_points_log` VALUES ('71', '16', '新用户注册', '欢迎新用户，注册即送3点积分', '3', '3', 'new_user_register', '1473643313');
INSERT INTO `tl_user_points_log` VALUES ('72', '16', '新用户注册活动', '活动期间再送2点', '2', '5', 'new_user_register_activity', '1473643313');
INSERT INTO `tl_user_points_log` VALUES ('73', '2', '每日答题活动', '完成今日答题，获得1点积分', '1', '6', 'answer_question_every_day_activity', '1474264662');
INSERT INTO `tl_user_points_log` VALUES ('74', '1', '每日答题活动', '完成今日答题，获得1点积分', '1', '2', 'answer_question_every_day_activity', '1474268901');
INSERT INTO `tl_user_points_log` VALUES ('75', '1', '由订单 D0920147436440626410 消费', '2016-09-20 17:40:06生成订单 D0920147436440626410 ，用户使用积分：2', '-2', '0', 'goods_consume', '1474364406');
INSERT INTO `tl_user_points_log` VALUES ('76', '1', '取消订单 D0920147436440626410 ，恢复积分', '2016-09-20 17:40:23取消订单 D0920147436440626410 ，用户恢复积分：2', '2', '2', 'order_cancel', '1474364423');
INSERT INTO `tl_user_points_log` VALUES ('77', '1', '由订单 D0920147436447257765 消费', '2016-09-20 17:41:12生成订单 D0920147436447257765 ，用户使用积分：2', '-2', '0', 'goods_consume', '1474364472');

-- ----------------------------
-- Table structure for `tl_user_receipt_address`
-- ----------------------------
DROP TABLE IF EXISTS `tl_user_receipt_address`;
CREATE TABLE `tl_user_receipt_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '对应用户id',
  `address` varchar(255) DEFAULT '' COMMENT '详细地址',
  `is_default` tinyint(1) DEFAULT '0' COMMENT '是否被标记为默认收货地址',
  `inputtime` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='用户收货地址信息表';

-- ----------------------------
-- Records of tl_user_receipt_address
-- ----------------------------
INSERT INTO `tl_user_receipt_address` VALUES ('7', '1', 'sadasdasd', '1', '1465898392', '1473133484');
INSERT INTO `tl_user_receipt_address` VALUES ('12', '14', '大师大师大师的啊', '1', '1467195944', '1467195960');
INSERT INTO `tl_user_receipt_address` VALUES ('15', '1', '啊实打实大山大啊实打实的', '0', '1472545597', '1473133484');
INSERT INTO `tl_user_receipt_address` VALUES ('18', '2', '我家厕所酷炫~~~~', '1', '1474267059', '1474267059');
